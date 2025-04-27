<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BabysitterProfileCertification extends Model
{
    use HasFactory;

    protected $fillable = [
        'babysitter_profile_id',
        'title',
        'description',
    ];

    public function profile()
    {
        return $this->belongsTo(BabysitterProfile::class, 'babysitter_profile_id');
    }
}
