<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Payment;

class PaymentController extends Controller
{
    public function history()
    {
        $payments = auth()->user()->payments()->latest()->get();

        return view('subscriptions.payments-history', compact('payments'));
    }
}
