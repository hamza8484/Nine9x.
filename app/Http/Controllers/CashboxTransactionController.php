<?php

namespace App\Http\Controllers;

use App\CashboxTransaction;
use App\Cashbox;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;  // لتحسين الأداء باستخدام الترانزاكشن
use Illuminate\Support\Facades\Log;  // لتسجيل الأحداث أو الأخطاء

class CashboxTransactionController extends Controller
{
    // إضافة Middleware للتحقق من تسجيل الدخول
    public function __construct()
    {
        $this->middleware('auth');
    }

    // لعرض جميع المعاملات
    public function index($cashbox_id = null)
    {
        $transactions = CashboxTransaction::when($cashbox_id, function ($query, $cashbox_id) {
            return $query->where('cashbox_id', $cashbox_id);
        })->latest()->paginate(15);

        $cashboxes = Cashbox::all();
        $cashbox_id = $cashboxes->isNotEmpty() ? $cashboxes->first()->id : $cashbox_id;

        return view('cashboxes.transactions', compact('transactions', 'cashboxes', 'cashbox_id'));
    }


    // لعرض تفاصيل معاملة واحدة (لـ Cashbox)
    public function show($cashbox_id)
    {
        $cashbox = Cashbox::findOrFail($cashbox_id);
        $transactions = CashboxTransaction::where('cashbox_id', $cashbox_id)
            ->orderBy('created_at', 'asc')
            ->get();

        $runningBalance = $cashbox->opening_balance ?? 0;

        foreach ($transactions as $transaction) {
            if ($transaction->type === 'deposit') {
                $runningBalance += $transaction->cash_amount;
            } elseif ($transaction->type === 'withdrawal') {
                $runningBalance -= $transaction->cash_amount;
            }

            // إضافة حقل الرصيد المرحلي لكل معاملة
            $transaction->running_balance = $runningBalance;
        }

        return view('cashboxes.transactions', compact('cashbox', 'transactions', 'cashbox_id'));
    }


    // لعرض نموذج إضافة معاملة جديدة
    public function create(Request $request)
    {
        $cashbox_id = $request->query('cashbox_id');  // أخذ cashbox_id من الاستعلام
        $cashbox = Cashbox::findOrFail($cashbox_id);  // جلب الخزنة المحددة
        $transactions = CashboxTransaction::getTransactionsByCashbox($cashbox_id);  // جلب المعاملات الخاصة بالخزنة
        $cashboxes = Cashbox::all();  // جلب جميع الخزائن

        return view('cashboxes.transactions', compact('cashbox', 'transactions', 'cashboxes', 'cashbox_id'));
    }

    // لتخزين معاملة جديدة
    public function store(Request $request)
    {
        // تحقق من صحة البيانات الواردة
        $request->validate([
            'cashbox_id' => 'required|exists:cashboxes,id',  // التأكد من أن الخزنة موجودة
            'type' => 'required|in:deposit,withdrawal',  // نوع المعاملة (إيداع أو سحب)
            'cash_amount' => 'required|numeric|min:0.01',  // التأكد من أن المبلغ أكبر من الصفر
            'cash_description' => 'nullable|string|max:255',  // وصف المعاملة (اختياري)
        ]);

        // بدء المعاملة باستخدام الترانزاكشن لضمان سلامة العملية
        DB::beginTransaction();
        
        try {
            // جلب الخزنة بناءً على الـ ID
            $cashbox = Cashbox::findOrFail($request->cashbox_id);

            // التحقق من نوع المعاملة (إيداع أو سحب)
            if ($request->type == 'deposit') {
                $cashbox->cash_balance += $request->cash_amount;  // زيادة الرصيد عند الإيداع
            } elseif ($request->type == 'withdrawal') {
                // التحقق من أن الرصيد كافٍ للسحب
                if ($cashbox->cash_balance < $request->cash_amount) {
                    // في حال كان الرصيد غير كافٍ
                    return redirect()->back()->withErrors(['cash_amount' => 'الرصيد غير كافٍ للسحب من الخزنة']);
                }
                $cashbox->cash_balance -= $request->cash_amount;  // تقليل الرصيد عند السحب
            }

            // حفظ التغييرات في الخزنة
            $cashbox->save();

            // حفظ المعاملة في جدول CashboxTransaction
            CashboxTransaction::create([
                'cashbox_id' => $request->cashbox_id,
                'type' => $request->type,
                'cash_amount' => $request->cash_amount,
                'user_id' => auth()->id(),  // استخدام ID المستخدم الحالي
                'cash_description' => $request->cash_description,
            ]);

            // تأكيد التغييرات
            DB::commit();

            // إعادة التوجيه إلى صفحة المعاملات الخاصة بالخزنة مع رسالة نجاح
            return redirect()->route('transactions.index')
                             ->with('success', 'تم إضافة المعاملة بنجاح');
        } catch (\Exception $e) {
            // في حال حدوث أي خطأ، نقوم بالتراجع عن العمليات
            DB::rollBack();

            // سجل الخطأ للرجوع إليه في المستقبل (يمكنك استخدام Log للتسجيل)
            Log::error('Failed to process cashbox transaction', ['error' => $e->getMessage()]);

            // عرض رسالة خطأ للمستخدم
            return redirect()->back()->withErrors(['error' => 'حدث خطأ أثناء إضافة المعاملة. يرجى المحاولة لاحقًا.']);
        }
    }
}
