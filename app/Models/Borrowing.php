<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Borrowing extends Model
{
    protected $fillable = [
        'member_id',
        'ebook_id',
        'borrow_date',
        'due_date',
        'return_date',
        'status',
        'fine_amount',
    ];

    protected function casts(): array
    {
        return [
            'borrow_date'  => 'date',
            'due_date'     => 'date',
            'return_date'  => 'date',
            'fine_amount'  => 'decimal:2',
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

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }
}
