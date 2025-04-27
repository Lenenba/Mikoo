<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BabysitterProfilePhoto extends Model
{
    use HasFactory;

    protected $fillable = [
        'babysitter_profile_id',
        'url',
        'is_profile_picture',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    public function babysitterProfile()
    {
        return $this->belongsTo(BabysitterProfile::class, 'babysitter_profile_id');
    }

    /**
     * Get the URL of the photo.
     *
     * @return string
     */
    public function scopeIsProfilePicture($query)
    {
        return $query->where('is_profile_picture', true);
    }
}
