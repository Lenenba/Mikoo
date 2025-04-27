<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;

class Availability extends Model
{
    /** @use HasFactory<\Database\Factories\AvailabilityFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'start_date',
        'end_date',
        'start_time',
        'end_time',
        'is_available',
        'note',
    ];

    /**
     * Get the user that owns the availability.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope a query to only include customers of a given user.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $userId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope a query to only include availabilities for a specific date.
     *
     * @param  Builder  $query
     * @param  Carbon|null  $date  The date to check availability for (defaults to today if null).
     * @return Builder
     */
    public function scopeIsAvailable(Builder $query, ?Carbon $date = null): Builder
    {
        // Utilise la date d'aujourd'hui si aucune date n'est fournie
        $targetDate = $date ?? Carbon::today();
        return $query->whereDate('start_date', $targetDate);
    }

    /**
     * Check if availability is a date range.
     *
     * @return bool
     */
    public function isDateRange(): bool
    {
        return $this->start_date && $this->end_date && $this->start_date !== $this->end_date;
    }

    /**
     * Set single date availability.
     *
     * @param string $date
     * @return void
     */
    public function setSingleDate(string $date): void
    {
        $this->start_date = $date;
        $this->end_date = $date;
    }

    /**
     * Return the number of distinct days in the current month
     * where the given user has availability.
     *
     * @param  int         $userId
     * @param  Carbon|null $date   Defaults to today()
     * @return int
     */
    public static function countAvailableDaysThisMonth(int $userId, ?Carbon $date = null): int
    {
        // Use today if no date provided
        $date = $date ?? Carbon::today();
        $startOfMonth = $date->copy()->startOfMonth();
        $endOfMonth   = $date->copy()->endOfMonth();

        // Fetch availabilities overlapping the month
        $availabilities = static::query()
            ->where('user_id', $userId)
            ->whereDate('start_date', '>=', $startOfMonth)
            ->whereDate('end_date', '<=', $endOfMonth)
            ->get();

        $uniqueDays = [];
        foreach ($availabilities as $avail) {
            // Determine the overlap range [start, end] within the month
            $start = Carbon::parse($avail->start_date)->max($startOfMonth);
            $end   = Carbon::parse($avail->end_date)->min($endOfMonth);

            // Iterate each day in the overlap and collect unique dates
            for (
                $d = $start->copy();
                $d->lte($end);
                $d->addDay()
            ) {
                $uniqueDays[$d->toDateString()] = true;
            }
        }

        // The number of distinct days available
        return count($uniqueDays);
    }

    /**
     * Set availability date range.
     *
     * @param string $startDate
     * @param string $endDate
     * @return void
     */
    public function setDateRange(string $startDate, string $endDate): void
    {
        $this->start_date = $startDate;
        $this->end_date = $endDate;
    }

    /**
     * Check if a given date is within the availability.
     *
     * @param string $date
     * @return bool
     */
    public function includesDate(string $date): bool
    {
        $target = Carbon::parse($date);
        $start = Carbon::parse($this->start_date);
        $end = Carbon::parse($this->end_date);

        return $target->between($start, $end);
    }
}
