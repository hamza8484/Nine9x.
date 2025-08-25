<?php

namespace App\Http\Controllers;

use App\Cashbox;
use App\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CashboxController extends Controller
{
    public function index()
        {
            $accounts = Account::all();
            $cashboxes = Cashbox::with('accounts')->get();
            $cashbox_id = $cashboxes->isNotEmpty() ? $cashboxes->first()->id : null;

            return view('cashboxes.cashboxes', compact('cashboxes', 'cashbox_id', 'accounts'));
        }


    public function create()
    {
        $accounts = Account::all(); // جلب جميع الحسابات
        return view('cashboxes.create', compact('accounts')); // تمرير الحسابات إلى العرض
    }

    public function store(Request $request)
    {
        $request->validate([
            'cash_name' => 'required|string|max:255',
            'cash_balance' => 'required|numeric',
            'cashbox_type' => 'required|in:main,sub,temporary,revenue,expense',
            'currency_code' => 'required|string|max:3|in:SAR,USD,AED,JOD',
            'cash_limit' => 'nullable|numeric|min:0',
            'status' => 'required|in:active,inactive,closed',
            'notes' => 'nullable|string',
            'account_ids' => 'required|array',
            'account_ids.*' => 'exists:accounts,id',
            'start_date' => 'nullable|date',
            'limit_effective_date' => 'nullable|date',
        ]);

        try {
            $cashbox = Cashbox::create([
                'cash_name' => $request->cash_name,
                'cash_balance' => $request->cash_balance,
                'user_id' => auth()->id(),
                'cashbox_type' => $request->cashbox_type,
                'currency_code' => $request->currency_code,
                'cash_limit' => $request->cash_limit,
                'status' => $request->status,
                'notes' => $request->notes,
                'start_date' => $request->start_date,
                'limit_effective_date' => $request->limit_effective_date,
                'created_by' => auth()->id(),
            ]);

            // ربط الحسابات بالخزنة
            $cashbox->accounts()->attach($request->account_ids);

            return redirect()->route('cashboxes.index')->with('success', 'تم إضافة الخزنة بنجاح');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect()->route('cashboxes.create')->with('error', 'حدث خطأ يرجى المحاولة لاحقًا.');
        }
    }


    public function show(Cashbox $cashbox)
    {
        return view('cashboxes.show', compact('cashbox'));
    }

    public function edit(Cashbox $cashbox)
    {
        $accounts = Account::all(); // جلب جميع الحسابات
        return view('cashboxes.edit', compact('cashbox', 'accounts')); // تمرير الحسابات إلى العرض
    }

    public function update(Request $request, Cashbox $cashbox)
    {
        $request->validate([
            'cash_name' => 'required|string|max:255',
            'cash_balance' => 'required|numeric',
            'cashbox_type' => 'required|in:main,sub,temporary,revenue,expense',
            'currency_code' => 'required|string|max:3|in:SAR,USD,AED,JOD',
            'cash_limit' => 'nullable|numeric|min:0',
            'status' => 'required|in:active,inactive,closed',
            'notes' => 'nullable|string',
            'account_ids' => 'required|array',
            'account_ids.*' => 'exists:accounts,id',
            'start_date' => 'nullable|date',
            'limit_effective_date' => 'nullable|date',
        ]);

        try {
            $cashbox->update([
                'cash_name' => $request->cash_name,
                'cash_balance' => $request->cash_balance,
                'cashbox_type' => $request->cashbox_type,
                'currency_code' => $request->currency_code,
                'cash_limit' => $request->cash_limit,
                'status' => $request->status,
                'notes' => $request->notes,
                'start_date' => $request->start_date,
                'limit_effective_date' => $request->limit_effective_date,
                'updated_by' => auth()->id(),
            ]);

            // تحديث الحسابات المرتبطة بالخزنة
            $cashbox->accounts()->sync($request->account_ids);

            return redirect()->route('cashboxes.index')->with('success', 'تم تحديث الخزنة بنجاح');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect()->route('cashboxes.edit', $cashbox->id)->with('error', 'حدث خطأ يرجى المحاولة لاحقًا.');
        }
    }


    public function destroy(Cashbox $cashbox)
    {
        $cashbox->delete(); // الحذف الناعم باستخدام SoftDeletes

        return redirect()->route('cashboxes.index')->with('success', 'تم حذف الخزنة بنجاح');
    }

    public function updateCashboxBalance(Request $request, $cashboxId)
    {
        $cashbox = Cashbox::find($cashboxId);

        if ($cashbox && $request->total_deu > 0) {
            $cashbox->cash_balance += $request->total_deu;

            if ($cashbox->save()) {
                return response()->json(['success' => true]);
            } else {
                return response()->json(['success' => false]);
            }
        }

        return response()->json(['success' => false]);
    }
}

