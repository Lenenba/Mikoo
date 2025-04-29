<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reservation extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'babysitter_id',
        'status',
        'notes',
        'is_recurring',
        'recurrence_rule',
        'recurrence_start_date',
        'recurrence_end_date',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_recurring'          => 'boolean',
        'recurrence_start_date' => 'date',
        'recurrence_end_date'   => 'date',
    ];

    /**
     * Get the user who made the reservation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the babysitter for this reservation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function babysitter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'babysitter_id');
    }

    /**
     * Scope a query to only include recurring reservations.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeRecurring($query)
    {
        return $query->where('is_recurring', true);
    }

    /**
     * Scope a query to only include reservations for a specific babysitter.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int  $babysitterId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope a query to only include non-recurring (single date) reservations.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSingleDate($query)
    {
        return $query->where('is_recurring', false);
    }

    /**
     * Scope a query to only include reservations for a specific babysitter.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int  $babysitterId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeIsPending($query)
    {
        return $query->where('status', 'pending');
    }
}
