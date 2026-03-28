<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Author extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'bio',
        'nationality',
    ];

    // ── Name Accessor ─────────────────────────────────────────────────────────

    public function getFullNameAttribute(): string
    {
        return trim(
            collect([$this->first_name, $this->middle_name, $this->last_name])
                ->filter()
                ->implode(' ')
        );
    }

    // ── Relationships ─────────────────────────────────────────────────────────

    public function ebooks(): BelongsToMany
    {
        return $this->belongsToMany(Ebook::class, 'ebook_authors');
    }
}
