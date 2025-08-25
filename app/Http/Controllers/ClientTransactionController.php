<?php

namespace App\Http\Controllers;

use App\Cashbox;
use App\Customer;
use App\ClientTransaction;
use Illuminate\Http\Request;
use App\Company;
use App\Account;
use Illuminate\Support\Facades\Log; // إضافة Log لتتبع العمليات

class ClientTransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // جلب المعاملات مع العملاء والمستخدمين
        $transactions = ClientTransaction::with('client', 'user')->get();
        
        // جلب كافة العملاء والخزائن
        $customers = Customer::all();
        $cashboxes = Cashbox::all();  // تحديث إلى Cashbox
        
        $accounts = Account::all();
        // جلب بيانات الشركة
        $company = Company::first(); // أو استخدم الشركة المناسبة
        
        return view('client_transactions.client_transactions', compact('transactions', 'customers', 'cashboxes', 'company','accounts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // جلب الخزائن
        $cashboxes = Cashbox::all();  // تحديث إلى Cashbox
        $customers = Customer::all();
        
        return view('client_transactions.create', compact('customers', 'cashboxes'));  // تحديث هنا أيضاً
    }

    /**
     * Store a newly created resource in storage.
     */
  public function store(Request $request)
        {
            
            // التحقق من صحة البيانات المدخلة
            $request->validate([
                'client_id' => 'required|exists:customers,id',
                'amount' => 'required|numeric',
                'payment_method' => 'required|in:نقدي,بطاقة',
                'date' => 'required|date',
                'cashbox_id' => 'required|exists:cashboxes,id',
                'notes' => 'nullable|string',
                'account_id' => 'nullable|exists:accounts,id', // إضافة تحقق لحقل الحساب
            ]);

            // استرجاع العميل وتحقق من الرصيد
            $customer = Customer::findOrFail($request->client_id);

            // تحقق من أن المبلغ لا يؤدي إلى رصيد سلبي
            if ($customer->cus_balance - $request->amount < 0) {
                return redirect()->back()->with('error', 'المبلغ المدخل يؤدي إلى رصيد سلبي للعميل.');
            }

            // إذا كان السند هو دفع من العميل يجب خصم المبلغ من الرصيد
            $newBalance = $customer->cus_balance - $request->amount; // خصم المبلغ من رصيد العميل

            // إنشاء السند وتحديث رصيد العميل
            $transaction = ClientTransaction::create([
                'amount' => $request->amount,
                'balance' => $newBalance, // يجب تحديثه بناءً على خصم المبلغ
                'payment_method' => $request->payment_method,
                'date' => $request->date,
                'user_id' => auth()->id(),
                'client_id' => $customer->id,
                'cashbox_id' => $request->cashbox_id, // تحديد الخزنة
                'account_id' => $request->account_id, // إضافة الحساب
                'notes' => $request->notes,
            ]);

            // تحديث رصيد العميل
            $customer->update(['cus_balance' => $newBalance]);

            // تحديث رصيد الخزنة
            $cashbox = Cashbox::findOrFail($request->cashbox_id);
            $cashbox->cash_balance += $request->amount; // الخزنة تزداد لأن العميل يدفع
            $cashbox->save();

            // تحديث رصيد الحساب إذا كان مرتبطًا بحساب
            if ($request->account_id) {
                $account = Account::findOrFail($request->account_id);
                $account->balance += $request->amount; // إضافة المبلغ إلى رصيد الحساب
                $account->save();
            }

            // إضافة Log لتتبع العمليات
            Log::info('Client transaction created', [
                'transaction_id' => $transaction->id, 
                'client_balance' => $newBalance, 
                'cashbox_balance' => $cashbox->cash_balance,
                'account_balance' => $request->account_id ? $account->balance : 'N/A'
            ]);

            // إعادة التوجيه إلى صفحة المعاملات مع رسالة نجاح
            return redirect()->route('client_transactions.index')->with('Add', 'تم إضافة سند القبض بنجاح');
        }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ClientTransaction $clientTransaction)
    {
        // جلب العملاء والخزائن لتحديث السند
        $customers = Customer::all();
        $cashboxes = Cashbox::all();  // تحديث إلى Cashbox
        
        return view('client_transactions.edit', compact('clientTransaction', 'customers', 'cashboxes'));
    }

    /**
     * Update the specified resource in storage.
     */
   public function update(Request $request, ClientTransaction $clientTransaction)
    {
        // التحقق من صحة البيانات المدخلة
        $request->validate([
            'amount' => 'required|numeric',
            'payment_method' => 'required|in:نقدي,بطاقة',
            'date' => 'required|date',
            'cashbox_id' => 'required|exists:cashboxes,id',
            'notes' => 'nullable|string'
        ]);

        // استرجاع العميل والتحقق من الرصيد
        $customer = $clientTransaction->client;
        $newBalance = $customer->cus_balance - $clientTransaction->amount + $request->amount;

        // تحقق من أن الرصيد الجديد لا يؤدي إلى رصيد سلبي
        if ($newBalance < 0) {
            return redirect()->back()->with('error', 'المبلغ المعدل يؤدي إلى رصيد سلبي للعميل.');
        }

        // تحديث السند والرصيد
        $clientTransaction->update([
            'amount' => $request->amount,
            'payment_method' => $request->payment_method,
            'date' => $request->date,
            'cashbox_id' => $request->cashbox_id,
            'account_id'=> $request->account_id,
            'notes' => $request->notes,
        ]);

        // تحديث رصيد العميل
        $customer->update(['cus_balance' => $newBalance]);

        return redirect()->route('client_transactions.index')->with('edit', 'تم تعديل السند بنجاح');
    }


    /**
     * Remove the specified resource from storage.
     */
   public function destroy(ClientTransaction $clientTransaction)
    {
        // استرجاع العميل وتحديث رصيده عند حذف السند
        $customer = $clientTransaction->client;
        $newBalance = $customer->cus_balance - $clientTransaction->amount;

        // تحقق من أن الحذف لا يؤدي إلى رصيد سلبي
        if ($newBalance < 0) {
            return redirect()->back()->with('error', 'حذف السند يؤدي إلى رصيد سلبي للعميل.');
        }

        // حذف السند
        $clientTransaction->delete();

        // تحديث رصيد العميل
        $customer->update(['cus_balance' => $newBalance]);

        return redirect()->route('client_transactions.index')->with('delete', 'تم حذف السند بنجاح');
    }


    public function getClientBalance($id)
    {
        $customer = Customer::find($id);

        if ($customer) {
            return response()->json(['cus_balance' => $customer->cus_balance]);
        }

        return response()->json(['cus_balance' => 0]);
    }

    public function showClientTransactions($customerId)
    {
        // جلب المعاملات الخاصة بالعميل
        $transactions = ClientTransaction::where('client_id', $customerId)
                                        ->with('cashbox', 'user') // جلب بيانات الخزنة والمستخدم المرتبط بالسند
                                        ->get();

        // جلب بيانات العميل
        $customer = Customer::findOrFail($customerId);
        
        return view('client_transactions.client_transactions', compact('transactions', 'customer'));
    }
}
