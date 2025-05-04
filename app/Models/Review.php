<?php

namespace App\Models;

use App\Models\ReviewPhoto;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Review extends Model
{
    /** @use HasFactory<\Database\Factories\ReviewFactory> */
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'work_id',
        'rating',
        'headline',
        'review',
    ];


    /**
     * Get photos for the review.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function photos()
    {
        return $this->hasMany(ReviewPhoto::class);
    }

    /**
     * Get the work (reservation) for this review.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function work()
    {
        return $this->belongsTo(Work::class);
    }
}
