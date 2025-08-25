<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = RouteServiceProvider::HOME;

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * تخصيص بيانات الاعتماد (credentials) لاستخدامها في عملية تسجيل الدخول.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    protected function credentials(\Illuminate\Http\Request $request)
    {
        return [
            'email' => $request->email,
            'password' => $request->password, 
            'Status' => 'مفعل', // التأكد من أن المستخدم مفعل
        ];
    }

    /**
     * تخصيص التحقق من بيانات المستخدم قبل محاولة الدخول.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validateLogin(\Illuminate\Http\Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',  // التحقق من صحة البريد الإلكتروني
            'password' => 'required|string',
        ]);

        // تحقق إضافي إذا كان البريد الإلكتروني مفعل
        $validator->after(function ($validator) use ($request) {
            $user = \App\Models\User::where('email', $request->email)->first();
            if ($user && $user->Status !== 'مفعل') {
                $validator->errors()->add('email', 'البريد الإلكتروني غير مفعل.');
            }
        });

        return $validator;
    }

    /**
     * تخصيص الرسائل التي يتم عرضها عند الفشل في تسجيل الدخول.
     *
     * @return array
     */
    public function getFailedLoginMessage()
    {
        return "فشل تسجيل الدخول. تأكد من صحة البريد الإلكتروني أو كلمة المرور.";
    }

    /**
     * تخصيص الرسائل التي يتم عرضها بعد تسجيل الدخول بنجاح.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function authenticated(Request $request, $user)
    {
        return redirect()->intended($this->redirectTo)->with('status', 'تم تسجيل الدخول بنجاح!');
    }
}
