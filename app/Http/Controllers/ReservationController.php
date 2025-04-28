<?php

namespace App\Http\Controllers;

use App\Http\Requests\Reservation\ReservationRequest;
use Carbon\Carbon;
use App\Models\User;
use Inertia\Inertia;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\ReservationToFullCalendarService;

class ReservationController extends Controller
{
    /**
     * Display a listing of the reservations.
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        // Fetch reservations with related user, ordered by creation date
        $reservations = Reservation::ByUser(Auth::id())
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(5);

        return Inertia::render('reservations/Index', [
            'reservations' => $reservations,
        ]);
    }


    /**
     * Show the form for booking a babysitter.
     *
     * @return \Inertia\Response
     */
    public function create(User $user, ReservationToFullCalendarService $calendarService)
    {
        // Fetch the babysitter's availability
        $reservations = Reservation::where('user_id', Auth::id())
            ->where('babysitter_id', $user->id)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get();

        // 2. Transformer pour FullCalendar
        $events = $calendarService->transform($reservations);
        // Pass the babysitter and their availability to the view
        return Inertia::render('reservations/Create', [
            'babysitter' => $user,
            'profilePicture' => $user?->profile?->photos()->isProfilePicture()->first()->url,
            'events' => $events,
        ]);
    }

    /**
     * Store a newly created reservation in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(ReservationRequest $reservationRequest)
    {
        $validated = $reservationRequest->validated();

        // Days of week convert
        $daysOfWeek = is_array($validated['days_of_week'] ?? null)
            ? implode(',', $validated['days_of_week'])
            : null;

        // Dates parsing
        $dtStart = Carbon::parse($validated['recurrence_start_date'] . ' ' . ($validated['recurrence_start_time'] ?? '00:00'))
            ->utc()
            ->format('Ymd');

        $until = Carbon::parse($validated['recurrence_end_date'] . ' ' . ($validated['recurrence_end_time'] ?? '23:59'))
            ->utc()
            ->format('Ymd');

        // Build RRULE parts
        $rruleParts = [
            'FREQ=' . strtoupper($validated['recurrence_freq']),
            'INTERVAL=' . ($validated['recurrence_interval'] ?? 1),
            'UNTIL=' . $until,
        ];

        if ($daysOfWeek && $validated['recurrence_freq'] === 'weekly') {
            $rruleParts[] = 'BYDAY=' . $daysOfWeek;
        }

        // Build full recurrence_rule string
        $rruleString = "DTSTART:$dtStart\nRRULE:" . implode(';', $rruleParts);

        // Create reservation
        Reservation::create([
            'user_id' => Auth::id(),
            'recurrence_rule' => $rruleString,
            ...$validated,
        ]);

        return redirect()->route('reservations.index')->with('success', __('Reservation created successfully.'));
    }
}
