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

    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }
}
