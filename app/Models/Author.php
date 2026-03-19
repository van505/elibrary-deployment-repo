<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Author extends Model
{
    protected $fillable = [
        'name',
        'bio',
        'nationality',
    ];

    public function ebooks(): HasMany
    {
        return $this->hasMany(Ebook::class);
    }
}
