<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PasswordHistory extends Model
{
    // Only track created_at (no updated_at needed)
    const UPDATED_AT = null;

    protected $table = 'password_history';

    protected $fillable = [
        'user_id',
        'password',
    ];

    protected $hidden = ['password'];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
