<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Company;


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {

        // جلب أول شركة من قاعدة البيانات
        $company = Company::first();
            if (!$company) {
                // إذا لم يتم العثور على بيانات الشركة، يمكن تحديد رسالة أو إجراء آخر
                return redirect()->back()->with('error', 'الشركة غير موجودة في قاعدة البيانات');
            }
           
        return view('home');
    }
}
