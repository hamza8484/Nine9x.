<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\JournalEntryLine;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    // دالة لعرض ميزان المراجعة
    public function showBalanceSheet($fiscalYearId)
    {
        // استعلام للحصول على بيانات ميزان المراجعة
        $journalEntries = JournalEntryLine::select('accounts.code as account_code', 'accounts.name as account_name')
            ->join('accounts', 'accounts.id', '=', 'journal_entry_lines.account_id')
            ->join('journal_entries', 'journal_entries.id', '=', 'journal_entry_lines.journal_entry_id')
            ->where('journal_entries.fiscal_year_id', $fiscalYearId)
            ->groupBy('accounts.code', 'accounts.name')
            ->selectRaw('
                SUM(CASE WHEN journal_entry_lines.entry_type = "debit" THEN journal_entry_lines.amount ELSE 0 END) AS total_debit,
                SUM(CASE WHEN journal_entry_lines.entry_type = "credit" THEN journal_entry_lines.amount ELSE 0 END) AS total_credit
            ')
        ->get();

        // إرسال البيانات إلى الـ view
        return view('report.balanceSheet', compact('journalEntries', 'fiscalYearId'));
    }


    // دالة لتحويل تقرير ميزان المراجعة إلى PDF (اختياري)
    public function generatePdf($fiscalYearId)
    {
        // الحصول على البيانات كما في دالة showBalanceSheet
        $journalEntries = JournalEntryLine::select('accounts.code as account_code', 'accounts.name as account_name')
            ->join('accounts', 'accounts.id', '=', 'journal_entry_lines.account_id')
            ->join('journal_entries', 'journal_entries.id', '=', 'journal_entry_lines.journal_entry_id')
            ->where('journal_entries.fiscal_year_id', $fiscalYearId)
            ->groupBy('accounts.code', 'accounts.name')
            ->selectRaw('SUM(CASE WHEN journal_entry_lines.entry_type = "debit" THEN journal_entry_lines.amount ELSE 0 END) AS total_debit')
            ->selectRaw('SUM(CASE WHEN journal_entry_lines.entry_type = "credit" THEN journal_entry_lines.amount ELSE 0 END) AS total_credit')
            ->get();

        // تحميل الـ view وتحويله إلى PDF
        $pdf = Pdf::loadView('report.balanceSheetPdf', compact('journalEntries', 'fiscalYearId'));

        // تحميل الملف بصيغة PDF
        return $pdf->download('balance_sheet_' . $fiscalYearId . '.pdf');
    }

}
