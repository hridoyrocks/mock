<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OtpVerification extends Model
{
    use HasFactory;

    protected $fillable = [
        'identifier',
        'token',
        'otp_code',
        'type',
        'expires_at',
        'verified_at',
        'attempts'
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'verified_at' => 'datetime',
    ];

    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    public function isVerified(): bool
    {
        return !is_null($this->verified_at);
    }

    public function incrementAttempts(): void
    {
        $this->increment('attempts');
    }

    public function markAsVerified(): void
    {
        $this->update(['verified_at' => now()]);
    }

    public static function generateOTP(): string
    {
        return str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
    }

    public static function createForEmail(string $email): self
    {
        // Delete old OTPs
        self::where('identifier', $email)
            ->where('type', 'email')
            ->delete();

        return self::create([
            'identifier' => $email,
            'token' => \Str::random(64),
            'otp_code' => self::generateOTP(),
            'type' => 'email',
            'expires_at' => now()->addMinutes(5),
        ]);
    }
}