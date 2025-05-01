<?php

namespace App\Models;

use App\Models\Reservation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Work extends Model
{
    /** @use HasFactory<\Database\Factories\WorkFactory> */
    use HasFactory;

    protected $fillable = [
        'reservation_id',
        'scheduled_for',
        'start_time',
        'end_time',
        'is_completed',
        'is_approved_by_parent',
        'invoiced_at',
    ];

    protected $casts = [
        'scheduled_for' => 'date',
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
        'is_completed' => 'boolean',
        'is_approved_by_parent' => 'boolean',
        'invoiced_at' => 'datetime',
    ];

    /**
     * Get the reservation associated with the work.
     */
    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }

    /**
     * scope for parent user.
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int  $userId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForParent($query, $userId)
    {
        return $query->whereHas('reservation', fn($q) => $q->where('user_id', $userId))
            ->with(['reservation.babysitter.profile']);
    }

    /**
     * scope for babysitter user.
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int  $userId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForBabysitter($query, $userId)
    {
        return $query->whereHas('reservation', fn($q) => $q->where('babysitter_id', $userId))
            ->with(['reservation.user.profile']);
    }

    /**
     * scope for admin user.
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithReservation($query)
    {
        return $query->with('reservation')->orderByDesc('scheduled_for');
    }
}
