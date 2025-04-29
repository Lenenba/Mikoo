<?php

namespace App\Http\Controllers;

use App\Http\Requests\Reservation\ReservationRequest;
use App\Models\User;
use App\Models\Reservation;
use App\Services\ReservationDataService;
use App\Services\ReservationToFullCalendarService;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Carbon\Carbon;
use RRule\RRule;

class ReservationController extends Controller
{
    /**
     * Display a listing of the reservations according to the current user's role.
     */
    public function index(ReservationDataService $dataService)
    {
        $user         = Auth::user();
        $reservations = $this->fetchReservationsForRole($user);

        $statsKeys = ['all', 'pending', 'canceled', 'confirmed'];
        $stats     = [];

        // Prepare filtered collections
        $collections = [
            'all'       => $reservations,
            'pending'   => $reservations->where('status', 'pending'),
            'canceled'  => $reservations->where('status', 'canceled'),
            'confirmed' => $reservations->where('status', 'confirmed'),
        ];

        // Build stats for each status
        foreach ($collections as $key => $collection) {
            $stats["{$key}Stats"]        = $dataService->getMonthlyReservationStats($collection);
            $stats["{$key}Count"]        = $collection->count();
            $stats["{$key}LastMonthCount"] = $collection->where('recurrence_start_date', '>=', now()->subMonth())->count();
        }

        return Inertia::render('reservations/Index', array_merge([
            'reservations'      => $dataService->transform($reservations),
            'lastUserReservation' => $reservations->last(),
        ], $stats));
    }

    /**
     * Show the form for booking a babysitter.
     */
    public function create(User $user, ReservationToFullCalendarService $calendarService)
    {
        $this->authorizeBooking($user);

        $myReservations = Reservation::with('user')
            ->where('user_id', Auth::id())
            ->where('babysitter_id', $user->id)
            ->with('user')
            ->latest()
            ->get();

        $events = $calendarService->transform($myReservations);

        return Inertia::render('reservations/Create', [
            'babysitter'     => $user,
            'profilePicture' => $user->profile?->photos()->isProfilePicture()->first()?->url,
            'events'         => $events,
        ]);
    }

    public function store(ReservationRequest $request)
    {
        $data = $request->validated();

        // 1️⃣ Build the RRULE string
        $rruleString = $this->buildRRule(
            $data['recurrence_freq'],
            $data['recurrence_interval'] ?? 1,
            $data['recurrence_start_date'],
            $data['recurrence_start_time'] ?? '00:00',
            $data['recurrence_end_date'],
            $data['recurrence_end_time'] ?? '23:59',
            $data['days_of_week'] ?? []
        );

        // 2️⃣ Instancier l'objet RRule pour la nouvelle réservation
        $newRule = new RRule($rruleString);

        // 3️⃣ Récupérer les réservations actives du user/babysitter
        $existingReservations = Reservation::where('user_id', Auth::id())
            ->where('babysitter_id', $data['babysitter_id'])
            ->where('status', '!=', 'canceled')
            ->whereNotNull('recurrence_rule')
            ->get(['recurrence_rule']);

        // 4️⃣ Vérifier chaque occurrence de la nouvelle règle
        foreach ($newRule as $occurrence) {
            /** @var \DateTimeInterface $occurrence */
            foreach ($existingReservations as $res) {
                $existingRule = new RRule($res->recurrence_rule);

                // Si une occurrence existe déjà pour le même jour
                if ($this->occursOnSameDay($existingRule, $occurrence)) {
                    return back()
                        ->with('error', __('Vous avez déjà une réservation active ce jour-là.'));
                }
            }
        }

        // 5️⃣ Création de la réservation
        Reservation::create(array_merge($data, [
            'user_id'         => Auth::id(),
            'recurrence_rule' => $rruleString,
        ]));

        return redirect()
            ->route('reservations.index')
            ->with('success', __('Reservation created successfully.'));
    }

    /**
     * Check if a given RRule produces an occurrence on the same calendar day as $date.
     *
     * @param  RRule                 $rule
     * @param  \DateTimeInterface    $date
     * @return bool
     */
    private function occursOnSameDay(RRule $rule, \DateTimeInterface $date): bool
    {
        // Génère les occurrences dans la tranche [00:00, 23:59] de ce jour
        $dayStart = Carbon::instance($date)->startOfDay()->toDateTimeImmutable();
        $dayEnd   = Carbon::instance($date)->endOfDay()->toDateTimeImmutable();

        $occurrences = $rule->getOccurrencesBetween($dayStart, $dayEnd);
        return ! empty($occurrences);
    }

    /**
     * Fetch reservations based on user role.
     */
    private function fetchReservationsForRole(User $user)
    {
        $baseQuery = Reservation::with(['user.profile', 'babysitter.profile'])->orderByDesc('created_at');

        return match ($user->role->name) {
            env('PARENT_ROLE')      => $baseQuery->where('user_id', $user->id)->get(),
            env('BABYSITTER_ROLE')  => $baseQuery->where('babysitter_id', $user->id)->get(),
            env('SUPER_ADMIN_ROLE') => $baseQuery->get(),
            default                 => abort(403, __('Unauthorized action.')),
        };
    }

    /**
     * Prevent a user from booking themselves.
     */
    private function authorizeBooking(User $babysitter): void
    {
        if ($babysitter->id === Auth::id()) {
            abort(403, __('You cannot book yourself.'));
        }
    }

    /**
     * Build an iCal RRULE string from recurrence parameters.
     *
     * @param string       $freq
     * @param int          $interval
     * @param string       $startDate
     * @param string       $startTime
     * @param string       $endDate
     * @param string       $endTime
     * @param array<mixed> $daysOfWeek
     */
    private function buildRRule(
        string $freq,
        int $interval,
        string $startDate,
        string $startTime,
        string $endDate,
        string $endTime,
        array $daysOfWeek
    ): string {
        // Format DTSTART
        $dtStart = Carbon::parse("{$startDate} {$startTime}")
            ->utc()->format('Ymd');

        // Format UNTIL
        $until = Carbon::parse("{$endDate} {$endTime}")
            ->utc()->format('Ymd');

        $parts = [
            'FREQ=' . strtoupper($freq),
            'INTERVAL=' . $interval,
            'UNTIL=' . $until,
        ];

        if (strtolower($freq) === 'weekly' && !empty($daysOfWeek)) {
            $parts[] = 'BYDAY=' . implode(',', $daysOfWeek);
        }

        return "DTSTART:{$dtStart}\nRRULE:" . implode(';', $parts);
    }
}
