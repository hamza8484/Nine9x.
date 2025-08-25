<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class LanguageController extends Controller
{
    /**
     * تغيير اللغة في الجلسة
     * 
     * @param  string  $locale
     * @return \Illuminate\Http\RedirectResponse
     */
    public function changeLanguage($locale)
    {
        // تحقق من أن اللغة موجودة في اللغات المتاحة (مثلاً: en, ar)
        if (in_array($locale, ['en', 'ar'])) {
            // حفظ اللغة في الجلسة
            session()->put('locale', $locale);
            // تعيين اللغة في التطبيق
            App::setLocale($locale);
        }
        
        // إعادة التوجيه إلى الصفحة السابقة بعد تغيير اللغة
        return redirect()->back();
    }
}
