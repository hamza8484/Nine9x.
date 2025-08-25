<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Invoice;
use Carbon\Carbon;

class InvoicesReportController extends Controller
{
    public function index()
    {
        return view('reports.invoices_report');
    }

    public function Search_invoices(Request $request)
    {
        $rdio = $request->rdio;

        // في حالة البحث بنوع الفاتورة
        if ($rdio == 1) {
            // في حالة عدم تحديد تاريخ
            if ($request->type && $request->start_at == '' && $request->end_at == '') {
                $invoices = Invoice::select('*')->where('inv_payment', '=', $request->type)->get();
                $type = $request->type;
                return view('reports.invoices_report', compact('type'))->withDetails($invoices);
            }
            
            // في حالة تحديد تاريخ استحقاق
            else {
                // تأكد من أن التواريخ ليست فارغة قبل إرسالها إلى Carbon
                $start_at = $request->start_at ? Carbon::parse($request->start_at) : null;
                $end_at = $request->end_at ? Carbon::parse($request->end_at) : null;
                $type = $request->type;

                // تأكد من أن كلا من $start_at و $end_at غير فارغين قبل البحث في قاعدة البيانات
                if ($start_at && $end_at) {
                    $invoices = Invoice::whereBetween('inv_date', [$start_at, $end_at])
                                       ->where('inv_payment', '=', $request->type)
                                       ->get();
                } else {
                    $invoices = Invoice::where('inv_payment', '=', $request->type)->get();
                }

                return view('reports.invoices_report', compact('type', 'start_at', 'end_at'))->withDetails($invoices);
            }
        }

        // ===================================================================
        
        // في البحث برقم الفاتورة
        else {
            // تأكد من أن الحقل المستخدم في النموذج هو inv_number
            $invoices = Invoice::select('*')->where('inv_number', '=', $request->inv_number)->get();
            return view('reports.invoices_report')->withDetails($invoices);
        }
    }
}
