<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Member extends Model
{
    protected $fillable = [
        'user_id',
        'member_code',
        'phone',
        'address',
        'status',
    ];

    // ── Relationships ────────────────────────────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    public function ebookAccess(): HasMany
    {
        return $this->hasMany(EbookAccess::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    // ── Subscription Helpers ─────────────────────────────────────────────────

    public function activeSubscription(): ?Subscription
    {
        return $this->subscriptions()
            ->where('status', 'active')
            ->where(function ($q) {
                $q->whereNull('expires_at')
                  ->orWhere('expires_at', '>', now());
            })
            ->latest()
            ->first();
    }

    public function currentPlan(): ?SubscriptionPlan
    {
        $sub = $this->activeSubscription();
        return $sub ? $sub->plan : null;
    }

    public function canAccessEbook(int $ebookId): bool
    {
        $plan = $this->currentPlan();
        if (! $plan) {
            return false;
        }

        $hasAccess = $this->ebookAccess()->where('ebook_id', $ebookId)->exists();
        if ($hasAccess) {
            return true;
        }

        $limit = $plan->ebook_limit;
        if ($limit === -1) {
            return true; // unlimited
        }

        return $this->ebookAccess()->count() < $limit;
    }
}
