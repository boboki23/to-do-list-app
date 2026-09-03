<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class PasswordResetOtp extends Model
{
    protected $fillable = [
        'email', 'otp', 'expires_at', 'is_used', 'attempts'
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'is_used' => 'boolean',
    ];

    public function isExpired(): bool
    {
        return Carbon::now()->greaterThan($this->expires_at);
    }

    public function isMaxAttempts(): bool
    {
        return $this->attempts >= 3;
    }

    public function scopeValid($query)
    {
        return $query->where('is_used', false)
                     ->where('expires_at', '>', Carbon::now());
    }
}