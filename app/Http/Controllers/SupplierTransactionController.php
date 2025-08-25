<?php

namespace App\Http\Controllers;


use App\Cashbox;
use App\Supplier;
use App\SupplierTransaction;
use Illuminate\Http\Request;
use App\Company;


class SupplierTransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // جلب المعاملات
        $transactions = SupplierTransaction::with('supplier', 'user')->get();

        // جلب جميع الموردين
        $suppliers = Supplier::all();

        // جلب جميع الخزائن
        $cashboxes = Cashbox::all();

        $company = Company::first(); // أو استخدم الشركة المناسبة
        // تمرير المعاملات والموردين والخزائن إلى الـ View
        return view('supplier_transactions.supplier_transactions', compact('transactions', 'suppliers', 'cashboxes', 'company'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $suppliers = Supplier::all();
        return view('supplier_transactions.create', compact('suppliers'));
    }

    /**
     * Store a newly created resource in storage.
     */
   public function store(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'amount' => 'required|numeric',
            'payment_method' => 'required|in:نقدي,بطاقة',
            'date' => 'required|date',
            'cashbox_id' => 'required|exists:cashboxes,id',
            'notes' => 'nullable|string'
        ]);

        $supplier = Supplier::findOrFail($request->supplier_id);

        if ($supplier->sup_balance < $request->amount) {
            return redirect()->back()->with('error', 'رصيد المورد غير كافٍ.');
        }

        $cashbox = Cashbox::findOrFail($request->cashbox_id);

        if ($cashbox->cash_balance < $request->amount) {
            return redirect()->back()->with('error', 'رصيد الخزنة غير كافٍ لإجراء الدفع.');
        }

        $newBalance = $supplier->sup_balance - $request->amount;
        $balanceBeforeTransaction = $supplier->sup_balance;

        $transaction = SupplierTransaction::create([
            'amount' => $request->amount,
            'balance' => $newBalance,
            'balance_before_transaction' => $balanceBeforeTransaction,
            'payment_method' => $request->payment_method,
            'date' => $request->date,
            'user_id' => auth()->id(),
            'supplier_id' => $supplier->id,
            'cashbox_id' => $cashbox->id,
            'notes' => $request->notes,
        ]);

        $supplier->update(['sup_balance' => $newBalance]);

        $cashbox->cash_balance -= $request->amount;
        $cashbox->save();

        // ✅ خصم المبلغ من الحساب المرتبط بالخزنة إن وجد
        if ($cashbox->account_id) {
            $account = \App\Account::find($cashbox->account_id);
            if ($account) {
                $account->balance -= $request->amount;
                $account->save();
            }
        }

        return redirect()->route('supplier_transactions.index')->with('success', 'تم إضافة سند الصرف بنجاح');
    }



    /**
     * Display the specified resource.
     */
    public function show(SupplierTransaction $supplierTransaction)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SupplierTransaction $supplierTransaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SupplierTransaction $supplierTransaction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SupplierTransaction $supplierTransaction)
    {
        //
    }

    /**
     * دالة لجلب رصيد المورد بناءً على الـ supplier_id
     */
    public function getSupplierBalance(Request $request)
        {
            // العثور على المورد بناءً على الـ supplier_id
            $supplier = Supplier::find($request->supplier_id);
            
            // إذا تم العثور على المورد، إرجاع الرصيد
            if ($supplier) {
                return response()->json(['balance' => $supplier->sup_balance]); // تأكد من أن `sup_balance` هو الحقل الصحيح في جدول الموردين
            }
            
            // في حالة عدم العثور على المورد
            return response()->json(['balance' => 0]);
        }

}
