<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Availability;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * get availibilities associated with the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function availabilities()
    {
        return $this->hasMany(Availability::class);
    }

    /**
     * Get the reservations made by this user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    /**
     * Get the role made by this user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Get the profile associated with the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function profile()
    {
        return $this->hasOne(BabysitterProfile::class, 'user_id')
            ->with(['photos', 'certifications']);
    }

    /**
     * Scope a query to only include users with the 'Babysitter' role.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder // Good practice to add return type hint
     */
    public function scopeFindBabysitter(Builder $query): Builder // Original name
    {
        // This correctly filters users by checking the name
        // of their related Role model.
        return $query->whereHas('role', function ($subQuery) { // Renamed inner var for clarity
            $subQuery->where('name', 'Babysitter');
        });
    }

    /**
     * Scope a query to order products by the most recent.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeMostRecent(Builder $query): Builder
    {
        return $query->orderByDesc('created_at');
    }

    /**
     * Scope a query to filter products based on given criteria.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param array $filters
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFilter(Builder $query, array $filters): Builder
    {
        return $query->when(
            $filters['name'] ?? null,
            fn($query, $name) => $query->where('name', 'like', '%' . $name . '%')
        );
    }

    /**
     * Check if the user has a specific role.
     *
     * @param string $role
     * @return bool
     */
    public function isSuperAdmin(): bool
    {
        return $this->hasRole('SuperAdmin');
    }

    /**
     * Check if the user has a specific role.
     *
     * @param string $role
     * @return bool
     */
    public function isBabysitter(): bool
    {
        return $this->hasRole('Babysitter');
    }

    /**
     * Check if the user has a specific role.
     *
     * @param string $role
     * @return bool
     */
    public function isParent(): bool
    {
        return $this->hasRole('Parent');
    }
}
