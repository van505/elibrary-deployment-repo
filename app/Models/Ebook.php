<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\EbookBookmark;

class Ebook extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'category_id',
        'title',
        'isbn',
        'publisher',
        'publish_year',
        'file_path',
        'cover_image',
        'file_type',
        'access_level',
        'is_featured',
        'is_spotlighted',
        'preview_pages',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
    ];

    // ── Relationships ─────────────────────────────────────────────────────────

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function authors(): BelongsToMany
    {
        return $this->belongsToMany(Author::class, 'ebook_authors');
    }

    public function ebookAccess(): HasMany
    {
        return $this->hasMany(EbookAccess::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function tags(): HasMany
    {
        return $this->hasMany(EbookTag::class);
    }

    // Alias used in admin analytics
    public function accesses(): HasMany
    {
        return $this->hasMany(EbookAccess::class);
    }

    public function bookmarks(): HasMany
    {
        return $this->hasMany(EbookBookmark::class);
    }
}
