<?php

namespace App\Http\Controllers;

use App\User;
use App\UserSubscription;
use App\Payment;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function statistics()
    {
        $totalUsers = User::count();
        $activeSubscriptions = UserSubscription::where('status', 'active')->count();
        $totalRevenue = Payment::where('payment_status', 'active')->sum('amount');
        $expiringSoon = UserSubscription::where('end_date', '<=', now()->addDays(7))->count();
    
        // تنبيهات داخل لوحة التحكم للمستخدمين الذين ستنتهي اشتراكاتهم قريباً
        $expiringSubscriptions = UserSubscription::where('end_date', '<=', now()->addDays(7))
            ->where('status', 'active')
            ->get();
    
        // الإيرادات حسب الشهر (آخر 6 شهور)
        $monthlyRevenue = Payment::select(
            DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month"),
            DB::raw("SUM(amount) as total")
        )
        ->where('payment_status', 'active')
        ->where('created_at', '>=', Carbon::now()->subMonths(6))
        ->groupBy('month')
        ->orderBy('month')
        ->get();
    
        $months = $monthlyRevenue->pluck('month');
        $revenues = $monthlyRevenue->pluck('total');
    
        // اشتراكات جديدة وتجديدات خلال آخر شهر
        $newSubscriptions = UserSubscription::whereDate('created_at', '>=', Carbon::now()->subMonth())->count();
    
        // تجديدات خلال آخر شهر
        $renewals = Payment::where('created_at', '>=', Carbon::now()->subMonth())
            ->whereIn('user_id', function ($query) {
                $query->select('user_id')
                    ->from('user_subscriptions')
                    ->groupBy('user_id')
                    ->havingRaw('count(*) > 1');
            })
            ->count();
    
        // عدد الاشتراكات لكل خطة
        $subscriptionsPerPlan = UserSubscription::with('plan')
            ->select('plan_id', DB::raw('count(*) as total'))
            ->groupBy('plan_id')
            ->get();
    
        // تفاصيل آخر الاشتراكات
        $recentSubscriptions = UserSubscription::withTrashed()
            ->with(['user', 'plan']) // تأكد أن العلاقات موجودة
            ->latest('created_at')
            ->limit(10)
            ->get();
    
        return view('dashboard.statistics', compact(
            'totalUsers',
            'activeSubscriptions',
            'totalRevenue',
            'expiringSoon',
            'months',
            'revenues',
            'newSubscriptions',
            'renewals',
            'subscriptionsPerPlan',
            'recentSubscriptions',
            'expiringSubscriptions'
        ));
    }
}
