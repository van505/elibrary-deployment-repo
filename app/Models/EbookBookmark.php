<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EbookBookmark extends Model
{
    public $timestamps = false;

    protected $fillable = ['member_id', 'ebook_id'];

    protected function casts(): array
    {
        return ['created_at' => 'datetime'];
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
