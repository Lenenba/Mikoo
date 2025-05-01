<?php

namespace App\Http\Controllers;

use RRule\RRule;
use Carbon\Carbon;
use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Reservation;
use Illuminate\Support\Facades\Auth;
use App\Services\ReservationDataService;
use App\Notifications\ReservationNotification;
use App\Services\ReservationToFullCalendarService;
use App\Http\Requests\Reservation\ReservationRequest;
use App\Http\Controllers\Utils\Traits\ReservationTrait;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ReservationController extends Controller
{
    use ReservationTrait, AuthorizesRequests;
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
            $data['recurrence_end_date'],
            $data['start_time'] ?? '00:00',
            $data['end_time'] ?? '23:59',
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
        $reservation = Reservation::create(array_merge($data, [
            'user_id'         => Auth::id(),
            'recurrence_rule' => $rruleString,
        ]));

        $reservation->start_time = Carbon::createFromFormat('H:i', $data['start_time']);
        $reservation->end_time   = Carbon::createFromFormat('H:i', $data['end_time']);
        $reservation->save();

        // English comment: notify the parent via the Notification system
        $reservation->user->notify(
            new ReservationNotification($reservation)
        );

        return redirect()
            ->route('reservations.index')
            ->with('success', __('Reservation created successfully.'));
    }

    /**
     * Display the specified reservation.
     *
     * @param  \App\Models\Reservation  $reservation
     * @return \Inertia\Response
     */
    public function show(int $reservationId): Response
    {
        $reservation = Reservation::with([
            'user.profile.photos',
            'babysitter.profile.photos',
        ])->findOrFail($reservationId);
        // English comment: ensure the user may view this reservation
        $this->authorize('view', $reservation);

        return Inertia::render('reservations/Show', [
            'reservation' => $reservation,
        ]);
    }
}
