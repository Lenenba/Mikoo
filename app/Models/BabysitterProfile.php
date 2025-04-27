<?php

namespace App\Models;

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BabysitterProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'birthdate',
        'phone',
        'address',
        'bio',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function photos()
    {
        return $this->hasMany(BabysitterProfilePhoto::class);
    }

    public function certifications()
    {
        return $this->hasMany(BabysitterProfileCertification::class);
    }
}
