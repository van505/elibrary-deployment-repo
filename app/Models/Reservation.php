<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reservation extends Model
{
    protected $fillable = [
        'member_id',
        'ebook_id',
        'reserved_date',
        'expiry_date',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'reserved_date' => 'date',
            'expiry_date'   => 'date',
        ];
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    public function ebook(): BelongsTo
    {
        return $this->belongsTo(Ebook::class);
    }
}
