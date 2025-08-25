<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\RetPurchase;
use Carbon\Carbon;

class ReturnpurchasesReportController extends Controller
{
    public function index()
    {
        return view('reports.ret_purchases_report');
    }

    public function Search_ret_purchases(Request $request)
    {
        $rdio = $request->rdio;
       
        // في حالة البحث بنوع الفاتورة
        if ($rdio == 1) {
            // في حالة عدم تحديد تاريخ
            if ($request->type && $request->start_at == '' && $request->end_at == '') {
                $ret_purchase = RetPurchase::select('*')->where('ret_pur_payment', '=', $request->type)->get();
                $type = $request->type;
                return view('reports.ret_purchases_report', compact('type'))->withDetails($ret_purchase);
            }
            
            // في حالة تحديد تاريخ استحقاق
            else {
                // تأكد من أن التواريخ ليست فارغة قبل إرسالها إلى Carbon
                $start_at = $request->start_at ? Carbon::parse($request->start_at) : null;
                $end_at = $request->end_at ? Carbon::parse($request->end_at) : null;
                $type = $request->type;

                // تأكد من أن كلا من $start_at و $end_at غير فارغين قبل البحث في قاعدة البيانات
                if ($start_at && $end_at) {
                    $ret_purchase = RetPurchase::whereBetween('ret_pur_date', [$start_at, $end_at])
                                       ->where('ret_pur_payment', '=', $request->type)
                                       ->get();
                } else {
                    $ret_purchase = RetPurchase::where('ret_pur_payment', '=', $request->type)->get();
                }

                return view('reports.ret_purchases_report', compact('type', 'start_at', 'end_at'))->withDetails($ret_purchase);
            }
        }

        // ===================================================================
        
        // في البحث برقم الفاتورة
        else {
            // تأكد من أن الحقل المستخدم في النموذج هو ret_pur_number
            $ret_purchase = RetPurchase::select('*')->where('ret_pur_number', '=', $request->ret_pur_number)->get();
            return view('reports.ret_purchases_report')->withDetails($ret_purchase);
        }
    }
}
