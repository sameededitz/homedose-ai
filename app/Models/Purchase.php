<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Purchase extends Model
{
    protected $fillable = [
        'user_id',
        'plan_id',
        'started_at',
        'expires_at',
        'is_active',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    // Method to automatically expire subscriptions
    public static function expireSubscriptions()
    {
        self::where('is_active', true)
            ->where('expires_at', '<=', now())
            ->update(['is_active' => false]);
    }
}
