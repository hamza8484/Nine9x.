<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\SubscriptionPlan;
use App\UserSubscription;
use App\Payment;
use App\Plan;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str; 
use Illuminate\Support\Facades\DB;

use Carbon\Carbon;

class SubscriptionController extends Controller
{
    // عرض جميع خطط الاشتراك
    public function plans()
    {
        $plans = SubscriptionPlan::all();
        
        return view('subscriptions.plans', compact('plans'));
    }

    // صفحة تأكيد الاشتراك  
    public function checkout($planId)
    {
        $plan = SubscriptionPlan::findOrFail($planId);
        return view('subscriptions.checkout', compact('plan'));
    }

    
    

    public function processCheckout(Request $request, $planId)
    {
        $request->validate([
            'card_holder' => 'required|string|max:255',
            'card_number' => 'required|string|min:16|max:19',
            'expiry_date' => 'required|string',
            'cvv' => 'required|numeric|min:3|max:9999',
            'payment_method' => 'required|string|in:credit_card',
        ]);

        // مثال على إنشاء سجل دفع
        $plan = SubscriptionPlan::findOrFail($planId);
        $userId = auth()->id();

         // تحقق إذا عنده اشتراك سابق
        $existingSubscription = UserSubscription::where('user_id', $userId)->first();

        // تحديد نوع العملية
        $isRenewal = $existingSubscription !== null;


        $payment = Payment::create([
            'user_id' => auth()->id(),
            'plan_id' => $planId,
            'payment_method' => $request->payment_method,
            'payment_status' => 'active', // أو pending لو الدفع حقيقي
            'amount' => $plan->price,
        ]);

        // تحديث حالة الاشتراك بعد الدفع
        $userSubscription = UserSubscription::updateOrCreate(
            ['user_id' => auth()->id()],
            [
                'plan_id' => $planId,
                'start_date' => Carbon::now(),
                'end_date' => Carbon::now()->addMonths($plan->duration_months ?? 1),
                'status' => 'active',
            ]
        );

        return redirect()->route('subscription.success')->with('success', $isRenewal ? 'تم تجديد الاشتراك بنجاح!' : 'تم الاشتراك بنجاح!');
    }


    // صفحة نجاح الاشتراك
    public function success()
    {
        return view('subscriptions.success');
    }

    // صفحة إدارة الاشتراك
    public function manage()
    {
        $subscriptions = UserSubscription::with(['user', 'plan'])
        ->where('user_id', auth()->id())
        ->get();
        
        return view('subscriptions.manage', compact('subscriptions'));
    }

    // تجديد الاشتراك بزر واحد
    public function renew()
    {
        $subscription = UserSubscription::where('user_id', auth()->id())->first();

        if (!$subscription) {
            return redirect()->route('subscription.plans')->withErrors('لا يوجد اشتراك لتجديده.');
        }

        $subscription->update([
            'start_date' => now(),
            'end_date' => now()->addMonths($subscription->plan->duration_months ?? 1),
        ]);

        return redirect()->route('subscription.manage')->with('success', 'تم تجديد اشتراكك بنجاح!');
    }

    public function update(Request $request, $id)
    {
        // التحقق من التواريخ المدخلة
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        // تحديث الاشتراك
        $subscription = UserSubscription::findOrFail($id);
        $subscription->start_date = $request->input('start_date');
        $subscription->end_date = $request->input('end_date');
        $subscription->save();

        return redirect()->route('subscription.manage')->with('success', 'تم تحديث الاشتراك بنجاح');
    }


}
