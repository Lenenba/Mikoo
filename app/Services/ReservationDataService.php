<?php

namespace App\Services;

use App\Models\Reservation;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class ReservationDataService
{
    /**
     * Transform a collection of Reservation models into an array suitable for data tables or JSON APIs.
     *
     * @param  Collection<int, Reservation>  $reservations
     * @return array<int, array<string, mixed>>
     */
    public function transform(Collection $reservations): array
    {
        return $reservations->map(fn(Reservation $reservation) => $this->transformRow($reservation))
            ->toArray();
    }

    /**
     * Transform a single Reservation instance into an associative array.
     *
     * @param  Reservation  $reservation
     * @return array<string, mixed>
     */
    protected function transformRow(Reservation $reservation): array
    {
        $parent = $reservation->user;
        $babysitter = $reservation->babysitter;

        // Resolve profile photos with fallback
        $parentPhotoUrl = $this->resolvePhotoUrl(
            $parent->profile?->photos->first(fn($p) => $p->is_profile_picture)?->url
        );
        $babysitterPhotoUrl = $this->resolvePhotoUrl(
            $babysitter->profile?->photos->first(fn($p) => $p->is_profile_picture)?->url
        );

        return [
            'id'                => $reservation->id,
            'status'            => $reservation->status,
            'notes'             => $reservation->notes,
            'is_recurring'      => (bool) $reservation->is_recurring,
            'recurrence_rule'   => $reservation->recurrence_rule,
            'start_date'        => optional($reservation->recurrence_start_date)?->toDateString(),
            'end_date'          => optional($reservation->recurrence_end_date)?->toDateString(),
            'start_time'        => $reservation->start_time,
            'end_time'          => $reservation->end_time,

            'parent' => [
                'id'          => $parent->id,
                'name'        => $parent->name,
                'email'       => $parent->email,
                'phone'       => $parent->phone,
                'photo_url'   => $parentPhotoUrl,
            ],

            'babysitter' => [
                'id'          => $babysitter->id,
                'name'        => $babysitter->name,
                'email'       => $babysitter->email,
                'phone'       => $babysitter->phone,
                'photo_url'   => $babysitterPhotoUrl,
            ],
        ];
    }

    /**
     * Return reservation counts grouped by month for the last 6 months,
     * using the provided Reservation collection.
     *
     * @param  Collection<int, Reservation>  $reservations
     * @return array<int, array{label: string, value: int}>
     */
    public function getMonthlyReservationStats(Collection $reservations): array
    {
        // Map month number to abbreviated French label
        $months = [
            1 => 'Jan',
            2 => 'Fév',
            3 => 'Mar',
            4 => 'Avr',
            5 => 'Mai',
            6 => 'Juin',
            7 => 'Juil',
            8 => 'Août',
            9 => 'Sept',
            10 => 'Oct',
            11 => 'Nov',
            12 => 'Déc',
        ];

        // Group reservations by month number (1–12)
        $grouped = $reservations
            ->groupBy(fn(Reservation $r) => (int) $r->recurrence_start_date->format('n'));

        $stats = [];

        // Loop over the last 6 months (including current)
        for ($i = 12; $i >= 0; $i--) {
            $date     = now()->subMonths($i);
            $monthNum = (int) $date->format('n');
            $label    = $months[$monthNum];
            $count    = isset($grouped[$monthNum]) ? $grouped[$monthNum]->count() : 0;

            $stats[] = [
                'label' => $label,
                'value' => $count,
            ];
        }

        return $stats;
    }

    /**
     * Resolve a stored photo path or full URL, providing a default if none.
     *
     * @param  string|null  $pathOrUrl
     * @return string
     */
    protected function resolvePhotoUrl(?string $pathOrUrl): string
    {
        $default = asset('images/defaultProfil.png');

        if (! $pathOrUrl) {
            return $default;
        }

        // If it's already a full URL
        if (str_starts_with($pathOrUrl, 'http://') || str_starts_with($pathOrUrl, 'https://')) {
            return $pathOrUrl;
        }

        // Otherwise assume it's a storage path
        return Storage::disk('public')->url($pathOrUrl);
    }
}
