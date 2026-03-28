<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubscriptionPlan extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'ebook_limit',
        'level',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'price'     => 'decimal:2',
            'is_active' => 'boolean',
            'level'     => 'integer',
        ];
    }

    /**
     * Returns true if this plan has a higher level than the given plan.
     */
    public function isHigherThan(self $other): bool
    {
        return $this->level > $other->level;
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class, 'plan_id');
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'plan_id');
    }
}
