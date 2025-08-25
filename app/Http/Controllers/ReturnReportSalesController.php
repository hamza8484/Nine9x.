<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ReturnInvoice;
use Carbon\Carbon;

class ReturnReportSalesController extends Controller
{
    public function index()
    {
        return view('reports.ret_invoices_report');
    }

    public function Search_ret_invoices(Request $request)
    {
        $rdio = $request->rdio;
       
        // في حالة البحث بنوع الفاتورة
        if ($rdio == 1) {
            // في حالة عدم تحديد تاريخ
            if ($request->type && $request->start_at == '' && $request->end_at == '') {
                $returnInvoices = ReturnInvoice::select('*')->where('ret_inv_payment', '=', $request->type)->get();
                $type = $request->type;
                return view('reports.ret_invoices_report', compact('type'))->withDetails($returnInvoices);
            }
            
            // في حالة تحديد تاريخ استحقاق
            else {
                // تأكد من أن التواريخ ليست فارغة قبل إرسالها إلى Carbon
                $start_at = $request->start_at ? Carbon::parse($request->start_at) : null;
                $end_at = $request->end_at ? Carbon::parse($request->end_at) : null;
                $type = $request->type;

                // تأكد من أن كلا من $start_at و $end_at غير فارغين قبل البحث في قاعدة البيانات
                if ($start_at && $end_at) {
                    $returnInvoices = ReturnInvoice::whereBetween('ret_inv_date', [$start_at, $end_at])
                                       ->where('ret_inv_payment', '=', $request->type)
                                       ->get();
                } else {
                    $returnInvoices = ReturnInvoice::where('ret_inv_payment', '=', $request->type)->get();
                }

                return view('reports.ret_invoices_report', compact('type', 'start_at', 'end_at'))->withDetails($returnInvoices);
            }
        }

        // ===================================================================
        
        // في البحث برقم الفاتورة
        else {
            // تأكد من أن الحقل المستخدم في النموذج هو ret_inv_number
            $returnInvoices = ReturnInvoice::select('*')->where('ret_inv_number', '=', $request->ret_inv_number)->get();
            return view('reports.ret_invoices_report')->withDetails($returnInvoices);
        }
    }
}
