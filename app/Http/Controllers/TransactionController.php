<?php

namespace App\Http\Controllers;

use App\Transaction;

class TransactionController extends Controller
{
    public function index()
    {
        // استرجاع العمليات مع العلاقات المرتبطة
        $transactions = Transaction::with(['user', 'account', 'cashbox', 'tax'])->get();

        // إرسالها للواجهة أو للـ API
        return view('transactions.livewire.account-tree', compact('transactions'));
        
        // لو شغال API تقدر ترجع JSON
        // return response()->json($transactions);
    }
}
