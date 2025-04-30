<?php

namespace App\Http\Controllers\Utils\Traits;

use RRule\RRule;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Reservation;
use Illuminate\Support\Facades\Auth;


trait ReservationTrait
{

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
