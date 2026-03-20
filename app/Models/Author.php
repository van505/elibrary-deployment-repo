<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Author extends Model
{
    protected $fillable = [
        'name',
        'bio',
        'nationality',
    ];

    public function ebooks(): BelongsToMany
    {
        return $this->belongsToMany(Ebook::class, 'book_authors');
    }
}
