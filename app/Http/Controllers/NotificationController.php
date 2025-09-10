<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Auth::user()->notifications()
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('notifications.index', compact('notifications'));
    }

    public function markAsRead($id)
    {
        $notification = Notification::findOrFail($id);
        
        // ตรวจสอบว่าเป็น notification ของผู้ใช้ที่ login อยู่
        if ($notification->user_id !== Auth::id()) {
            abort(403);
        }

        $notification->markAsRead();

        return response()->json([
            'success' => true,
            'message' => 'ทำเครื่องหมายว่าอ่านแล้ว'
        ]);
    }

    public function markAllAsRead()
    {
        Auth::user()->notifications()->unread()->update([
            'is_read' => true,
            'read_at' => now()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'ทำเครื่องหมายทั้งหมดว่าอ่านแล้ว'
        ]);
    }

    public function delete($id)
    {
        $notification = Notification::findOrFail($id);
        
        // ตรวจสอบว่าเป็น notification ของผู้ใช้ที่ login อยู่
        if ($notification->user_id !== Auth::id()) {
            abort(403);
        }

        $notification->delete();

        return response()->json([
            'success' => true,
            'message' => 'ลบการแจ้งเตือนแล้ว'
        ]);
    }

    public function getUnreadCount()
    {
        $count = Auth::user()->notifications()->unread()->count();

        return response()->json([
            'count' => $count
        ]);
    }

    public function getUnreadNotifications()
    {
        $notifications = Auth::user()->notifications()
            ->unread()
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return response()->json([
            'notifications' => $notifications
        ]);
    }
}
