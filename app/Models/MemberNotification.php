<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MemberNotification extends Model
{
    protected $table = 'member_notifications';

    protected $fillable = [
        'member_id',
        'type',
        'message',
        'is_read',
    ];

    protected function casts(): array
    {
        return ['is_read' => 'boolean'];
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }
}
