<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Collection extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'cover_image',
        'is_active',
        'created_by'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($collection) {
            if (empty($collection->slug)) {
                $collection->slug = static::generateUniqueSlug($collection->name);
            }
        });

        static::updating(function ($collection) {
            if ($collection->isDirty('name') && empty($collection->slug)) {
                $collection->slug = static::generateUniqueSlug($collection->name);
            }
        });
    }

    private static function generateUniqueSlug($name)
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $count = 1;

        while (static::where('slug', $slug)->exists()) {
            $slug = "{$originalSlug}-{$count}";
            $count++;
        }

        return $slug;
    }

    public function collectionEbooks(): HasMany
    {
        return $this->hasMany(CollectionEbook::class)->orderBy('order_number');
    }

    public function ebooks(): BelongsToMany
    {
        return $this->belongsToMany(Ebook::class, 'collection_ebooks')
                    ->withPivot('order_number', 'id')
                    ->orderBy('collection_ebooks.order_number');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
