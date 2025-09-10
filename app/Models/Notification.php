<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'title',
        'message',
        'action_url',
        'is_read',
        'read_at'
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime'
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function scopeRead($query)
    {
        return $query->where('is_read', true);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    // Methods
    public function markAsRead()
    {
        $this->update([
            'is_read' => true,
            'read_at' => now()
        ]);
    }

    public function markAsUnread()
    {
        $this->update([
            'is_read' => false,
            'read_at' => null
        ]);
    }

    // Static methods for creating notifications
    public static function createApprovalNotification($userId, $portfolioTitle, $personalInfoId)
    {
        return self::create([
            'user_id' => $userId,
            'type' => 'approval',
            'title' => 'Portfolio ได้รับการอนุมัติแล้ว',
            'message' => "Portfolio ของคุณ: '{$portfolioTitle}' ได้รับการอนุมัติแล้ว",
            'action_url' => route('personal-info.show', $personalInfoId)
        ]);
    }

    public static function createRejectionNotification($userId, $portfolioTitle, $reason = null)
    {
        $message = "Portfolio ของคุณ: '{$portfolioTitle}' ไม่ได้รับการอนุมัติ";
        if ($reason) {
            $message .= " เหตุผล: {$reason}";
        }

        return self::create([
            'user_id' => $userId,
            'type' => 'rejection',
            'title' => 'Portfolio ไม่ได้รับการอนุมัติ',
            'message' => $message,
            'action_url' => route('personal-info.edit-my', $userId)
        ]);
    }

    public static function createNewPortfolioNotification($adminUserId, $portfolioTitle, $studentName, $faculty, $major, $personalInfoId)
    {
        return self::create([
            'user_id' => $adminUserId,
            'type' => 'new_portfolio',
            'title' => 'มีผลงานใหม่รอการอนุมัติ',
            'message' => "นักศึกษา {$studentName} จาก {$faculty} สาขา{$major} ได้ส่งผลงาน '{$portfolioTitle}' มาให้ตรวจสอบ",
            'action_url' => route('personal-info.show', $personalInfoId)
        ]);
    }

    public static function createSystemNotification($userId, $title, $message, $actionUrl = null)
    {
        return self::create([
            'user_id' => $userId,
            'type' => 'system',
            'title' => $title,
            'message' => $message,
            'action_url' => $actionUrl
        ]);
    }
}
