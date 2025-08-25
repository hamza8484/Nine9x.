<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class NotificationController extends Controller
{
    public function index()
    {
        // الحصول على المستخدم الحالي
        $user = Auth::user();

        // حساب عدد الإشعارات غير المقروءة
        $unreadNotificationsCount = $user->unreadNotifications->count();

        // تحديث الإشعارات غير المقروءة كمقروءة
        $user->unreadNotifications->markAsRead();

        // إرسال المتغيرات إلى الـ view
        return view('notifications.index', compact('unreadNotificationsCount'));
    }
}



