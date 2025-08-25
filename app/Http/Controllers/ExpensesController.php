<?php

namespace App\Http\Controllers;

use App\Expense; 
use App\ECategory;
use App\Tax;
use App\Branch;
use App\Cashbox;
use App\Account;
use Illuminate\Http\Request;

class ExpensesController extends Controller
{
    /**
     * عرض قائمة المصروفات.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $expenses = Expense::with('eCategory')->get(); 
        $categories = ECategory::all();
        $taxes = Tax::all();
        
        return view('expenses.expenses', compact('expenses','categories','taxes'));
    }

    /**
     * عرض نموذج إضافة مصروف جديد.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // الحصول على أكبر رقم فاتورة حالياً
        $lastExpenses = Expense::orderBy('id', 'desc')->first();

        // إذا كانت هناك فواتير سابقة، نأخذ الرقم الأخير ونضيف 1 له
        $newExpensesNumber = $lastExpenses ? 'EXP-' . str_pad(substr($lastExpenses->exp_inv_number, 4) + 1, 6, '0', STR_PAD_LEFT) : 'EXP-000001';

        $categories = ECategory::all();  // الحصول على جميع الفئات
        $taxes = Tax::all();  // الحصول على جميع الضرائب
        $branches = Branch::all();  // الحصول على جميع الفروع
        $cashboxes = Cashbox::all();  // الحصول على جميع الخزائن
        $accounts = Account::all();  // الحصول على جميع الحسابات

        return view('expenses.create', compact('categories', 'taxes', 'branches', 'cashboxes', 'accounts', 'newExpensesNumber'));
    }

    /**
     * حفظ المصروف الجديد.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'exp_inv_number' => 'required|string|max:50',
            'exp_name' => 'required|string|max:255',
            'exp_date' => 'required|date',
            'exp_inv_name' => 'required|string|max:255',
            'exp_sup_number' => 'required|string|max:50',
            'exp_sup_date' => 'required|date',
            'category_id' => 'required|exists:e_categories,id',
            'exp_amount' => 'required|numeric',
            'exp_discount' => 'required|numeric',
            'tax_id' => 'required|exists:taxes,id',
            'tax_amount' => 'required|numeric',
            'final_amount' => 'required|numeric',
            'created_by' => 'required|string|max:255',
            'branch_id' => 'required|exists:branches,id',
            'cashbox_id' => 'nullable|exists:cashboxes,id',
            'account_id' => 'nullable|exists:accounts,id',
        ]);

        // حفظ المصروف
        $expense = Expense::create([
            'exp_inv_number' => $request->exp_inv_number,
            'exp_name' => $request->exp_name,
            'exp_date' => $request->exp_date,
            'exp_inv_name' => $request->exp_inv_name,
            'exp_sup_number' => $request->exp_sup_number,
            'exp_sup_date' => $request->exp_sup_date,
            'category_id' => $request->category_id,
            'exp_amount' => $request->exp_amount,
            'exp_discount' => $request->exp_discount,
            'tax_id' => $request->tax_id,
            'tax_amount' => $request->tax_amount,
            'final_amount' => $request->final_amount,
            'description' => $request->description,
            'created_by' => $request->created_by,
            'branch_id' => $request->branch_id,
            'cashbox_id' => $request->cashbox_id,
            'account_id' => $request->account_id,
            'user_id' => auth()->id(),
        ]);

        // ✅ تحديث الرصيد في الحساب المرتبط (إن وُجد)
        if ($request->filled('account_id')) {
            $account = Account::find($request->account_id);
            if ($account) {
                $account->balance -= $request->final_amount;
                $account->save();
            }
        }

        // ✅ تحديث الرصيد في الخزنة (إن وُجد)
        if ($request->filled('cashbox_id')) {
            $cashbox = Cashbox::find($request->cashbox_id);
            if ($cashbox) {
                // خصم المبلغ من الخزنة
               
                $cashbox->cash_balance -= $request->final_amount; // خصم المبلغ الجديد
                $cashbox->save();
            } else {
                // إذا لم تكن الخزنة موجودة
                \Log::error('Cashbox not found with id: ' . $request->cashbox_id);
            }
        }

        return redirect()->route('expenses.index')->with('success', 'تم حفظ المصروف وتحديث رصيد الحساب والخزنة.');
    }

    /**
     * عرض تفاصيل المصروف.
     *
     * @param  \App\Expense  $expense
     * @return \Illuminate\Http\Response
     */
    public function show(Expense $expense)
    {
        return view('expenses.show', compact('expense'));
    }

    /**
     * عرض نموذج تعديل المصروف.
     *
     * @param  \App\Expense  $expense
     * @return \Illuminate\Http\Response
     */
    public function edit(Expense $expense)
    {
        $categories = ECategory::all();
        $taxes = Tax::all();
        $branches = Branch::all();
        $cashboxes = Cashbox::all();
        $accounts = Account::all();
        return view('expenses.edit', compact('expense', 'categories', 'taxes', 'branches', 'cashboxes', 'accounts'));
    }

    /**
     * تحديث المصروف في قاعدة البيانات.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Expense  $expense
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Expense $expense)
    {
        // التحقق من البيانات المدخلة
        $request->validate([
            'exp_inv_number' => 'required|string|max:50',
            'exp_name' => 'required|string|max:255',
            'exp_date' => 'required|date',
            'exp_inv_name' => 'required|string|max:255',
            'exp_sup_number' => 'required|string|max:50',
            'exp_sup_date' => 'required|date',
            'category_id' => 'required|exists:e_categories,id',
            'exp_amount' => 'required|numeric',
            'exp_discount' => 'required|numeric',
            'tax_id' => 'required|exists:taxes,id',
            'tax_amount' => 'required|numeric',
            'final_amount' => 'required|numeric',
            'created_by' => 'required|string|max:255',
            'branch_id' => 'required|exists:branches,id',
            'cashbox_id' => 'nullable|exists:cashboxes,id',
            'account_id' => 'nullable|exists:accounts,id',
        ]);

        // حساب المبلغ القديم من المصروف لتحديث الرصيد
        $oldFinalAmount = $expense->final_amount;

        // تحديث المصروف
        $expense->update([
            'exp_inv_number' => $request->exp_inv_number,
            'exp_name' => $request->exp_name,
            'exp_date' => $request->exp_date,
            'exp_inv_name' => $request->exp_inv_name,
            'exp_sup_number' => $request->exp_sup_number,
            'exp_sup_date' => $request->exp_sup_date,
            'category_id' => $request->category_id,
            'exp_amount' => $request->exp_amount,
            'exp_discount' => $request->exp_discount,
            'tax_id' => $request->tax_id,
            'tax_amount' => $request->tax_amount,
            'final_amount' => $request->final_amount,
            'description' => $request->description,
            'created_by' => $request->created_by,
            'branch_id' => $request->branch_id,
            'cashbox_id' => $request->cashbox_id,
            'account_id' => $request->account_id,
            'user_id' => auth()->id(),
        ]);

        // ✅ تحديث الرصيد في الحساب (إن وُجد)
        if ($request->filled('account_id')) {
            $account = Account::find($request->account_id);
            if ($account) {
                // إعادة الرصيد القديم ثم خصم الجديد
                $account->balance += $oldFinalAmount; // إضافة المبلغ القديم
                $account->balance -= $request->final_amount; // خصم المبلغ الجديد
                $account->save();
            }
        }

        // ✅ تحديث الرصيد في الخزنة (إن وُجد)
        if ($request->filled('cashbox_id')) {
            $cashbox = Cashbox::find($request->cashbox_id);
            if ($cashbox) {
                // إعادة الرصيد القديم ثم خصم الجديد
                $cashbox->balance += $oldFinalAmount; // إضافة المبلغ القديم
                $cashbox->balance -= $request->final_amount; // خصم المبلغ الجديد
                $cashbox->save();
            } else {
                // إذا لم تكن الخزنة موجودة
                \Log::error('Cashbox not found with id: ' . $request->cashbox_id);
            }
        }

        return redirect()->route('expenses.index')->with('success', 'تم تحديث المصروف وتحديث الرصيد في الحساب والخزنة.');
    }

    /**
     * حذف المصروف.
     *
     * @param  \App\Expense  $expense
     * @return \Illuminate\Http\Response
     */
    public function destroy(Expense $expense)
    {
        $expense->delete();
        return redirect()->route('expenses.index')->with('success', 'تم حذف المصروف بنجاح');
    }
}
