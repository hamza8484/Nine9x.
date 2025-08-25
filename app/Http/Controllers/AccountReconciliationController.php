<?php


namespace App\Http\Controllers;

use App\AccountReconciliation;
use App\ReconciliationLine;
use App\Account;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ReconciliationExport;


use Illuminate\Http\Request;

class AccountReconciliationController extends Controller
{
   
    public function index(Request $request)
    {
        $query = AccountReconciliation::query();

        // تصفية حسب الحساب (بحث النص)
        if ($request->has('search') && $request->search != '') {
            $query->whereHas('account', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            });
        }

        // تصفية حسب تاريخ التسوية
        if ($request->has('reconciliation_date') && $request->reconciliation_date != '') {
            $query->whereDate('reconciliation_date', $request->reconciliation_date);
        }

        // تصفية حسب حالة التسوية
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // إحضار التسويات المالية
        $reconciliations = $query->get();

        // إرسال البيانات إلى الـ View
        return view('reconciliations.index', compact('reconciliations'));
    }

    // عرض تفاصيل تسوية مالية واحدة
    public function show($id)
    {
        $reconciliation = AccountReconciliation::with('lines', 'account')->findOrFail($id);
        return view('reconciliations.show', compact('reconciliation'));
    }

    // عرض نموذج إضافة تسوية جديدة
    public function create()
    {
        $accounts = Account::all();  // جلب جميع الحسابات
        return view('reconciliations.create', compact('accounts'));
    }

    // تخزين تسوية جديدة في قاعدة البيانات
    public function store(Request $request)
    {
        $request->validate([
            'account_id' => 'required',
            'reconciliation_date' => 'required|date',
            'system_balance' => 'required|numeric',
            'actual_balance' => 'required|numeric',
        ]);

        // إنشاء التسوية المالية
        $reconciliation = AccountReconciliation::create([
            'account_id' => $request->account_id,
            'reconciled_by' => auth()->id(),
            'reconciliation_date' => $request->reconciliation_date,
            'system_balance' => $request->system_balance,
            'actual_balance' => $request->actual_balance,
            'status' => 'pending',
            'notes' => $request->notes,
        ]);

        return redirect()->route('reconciliations.index')->with('success', 'تم إضافة التسوية بنجاح');
    }

    // تصدير التسوية إلى PDF
    public function exportPdf($id)
    {
        $reconciliation = AccountReconciliation::with('lines.journalEntryLine', 'account')->findOrFail($id);
        $pdf = Pdf::loadView('reconciliations.pdf', compact('reconciliation'));
        return $pdf->download("reconciliation_{$id}.pdf");
    }

    // تصدير التسوية إلى Excel
    public function exportExcel($id)
    {
        return Excel::download(new ReconciliationExport($id), "reconciliation_{$id}.xlsx");
    }

}

