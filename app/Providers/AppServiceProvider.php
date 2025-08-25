<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Auth;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('App\Helpers\NumberToWords', function () {
            return new \App\Helpers\NumberToWords();
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // تعيين اللغة من الجلسة
        if (Session::has('locale')) {
            App::setLocale(Session::get('locale'));
        }

        // تحديد الحد الأقصى للطول ليكون 191 (للأعمدة النصية في قاعدة البيانات)
        Schema::defaultStringLength(191);

        // تمرير المتغير unreadNotificationsCount لجميع الـ views
        View::composer('layouts.main-header', function ($view) {
            // التأكد من وجود مستخدم مسجل دخول
            if (Auth::check()) {
                $unreadNotificationsCount = Auth::user()->unreadNotifications->count();
                $view->with('unreadNotificationsCount', $unreadNotificationsCount);
            } else {
                $view->with('unreadNotificationsCount', 0);
            }
        });
    }
}

