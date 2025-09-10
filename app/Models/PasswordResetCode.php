<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class PasswordResetCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'code',
        'expires_at',
        'used',
        'used_at'
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'used_at' => 'datetime',
        'used' => 'boolean',
    ];

    /**
     * Check if the code is expired
     */
    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    /**
     * Check if the code is valid (not used and not expired)
     */
    public function isValid(): bool
    {
        return !$this->used && !$this->isExpired();
    }

    /**
     * Mark the code as used
     */
    public function markAsUsed(): void
    {
        $this->update([
            'used' => true,
            'used_at' => now()
        ]);
    }

    /**
     * Generate a new reset code
     */
    public static function generateCode(string $email): string
    {
        // ลบรหัสเก่าที่หมดอายุหรือถูกใช้แล้ว
        self::where('email', $email)
            ->where(function($query) {
                $query->where('used', true)
                      ->orWhere('expires_at', '<', now());
            })
            ->delete();

        // สร้างรหัสใหม่ 6 หลัก
        do {
            $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        } while (self::where('code', $code)->where('expires_at', '>', now())->exists());

        return $code;
    }

    /**
     * Create a new reset code for email
     */
    public static function createForEmail(string $email): self
    {
        $code = self::generateCode($email);
        
        return self::create([
            'email' => $email,
            'code' => $code,
            'expires_at' => now()->addMinutes(60), // หมดอายุใน 60 นาที
        ]);
    }

    /**
     * Find valid code by email and code
     */
    public static function findValid(string $email, string $code): ?self
    {
        return self::where('email', $email)
            ->where('code', $code)
            ->where('used', false)
            ->where('expires_at', '>', now())
            ->first();
    }

    /**
     * Clean up expired codes
     */
    public static function cleanupExpired(): int
    {
        return self::where('expires_at', '<', now())->delete();
    }
}
