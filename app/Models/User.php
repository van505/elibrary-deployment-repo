<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'email',
        'password',
        'role',
        'first_name',
        'last_name',
        'google_id',
        'two_factor_enabled',
        'google2fa_secret',
        'two_factor_recovery_codes',
        'email_otp_code',
        'email_otp_expires_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'google2fa_secret',
        'two_factor_recovery_codes',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'two_factor_enabled' => 'boolean',
            'google2fa_secret'   => 'encrypted',
            'two_factor_recovery_codes' => 'encrypted:array',
            'email_otp_expires_at' => 'datetime',
        ];
    }

    /**
     * Display name for admin: "First Last" if set, otherwise email.
     */
    public function getDisplayNameAttribute(): string
    {
        $name = trim(($this->first_name ?? '') . ' ' . ($this->last_name ?? ''));
        return $name ?: $this->email;
    }

    public function member(): HasOne
    {
        return $this->hasOne(Member::class);
    }

    public function activityLogs(): HasMany
    {
        return $this->hasMany(ActivityLog::class);
    }

    public function passwordHistory(): HasMany
    {
        return $this->hasMany(PasswordHistory::class);
    }
}
