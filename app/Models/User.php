<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, HasRoles, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'google_calendar_token',
        'google_calendar_refresh_token',
        'google_calendar_expires_at',
        'google_calendar_email',
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
            'google_calendar_token' => 'encrypted',
            'google_calendar_refresh_token' => 'encrypted',
            'google_calendar_expires_at' => 'datetime',
        ];
    }

    /** ¿Tiene el calendario de Google conectado? */
    public function hasGoogleCalendar(): bool
    {
        return ! empty($this->google_calendar_refresh_token);
    }

    /** Leads (prospectos) asignados a este usuario. */
    public function leads(): HasMany
    {
        return $this->hasMany(Lead::class);
    }
}
