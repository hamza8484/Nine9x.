<?php


namespace App\Http\Controllers;

use App\FiscalYear;
use Illuminate\Http\Request;

class FiscalYearController extends Controller
{
    public function index()
    {
        // جلب جميع السنوات المالية
        $fiscalYears = FiscalYear::all();

        // عرض الصفحة مع السنوات المالية
        return view('fiscal_years.index', compact('fiscalYears'));
    }

    /**
     * عرض صفحة إضافة سنة مالية جديدة.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('fiscal_years.create');
    }

    /**
     * حفظ سنة مالية جديدة.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // تحقق من المدخلات
        $request->validate([
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|string|in:نشطة,غير نشطة,مؤرشفة',
        ]);

        // إنشاء السنة المالية
        FiscalYear::create([
            'name' => $request->name,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'status' => $request->status,
        ]);

        // إعادة التوجيه مع رسالة نجاح
        return redirect()->route('fiscal_years.index')->with('success', 'تم إضافة السنة المالية بنجاح');
    }

    /**
     * عرض صفحة تعديل سنة مالية.
     *
     * @param  \App\Models\FiscalYear  $fiscalYear
     * @return \Illuminate\View\View
     */
    public function edit(FiscalYear $fiscalYear)
    {
        return view('fiscal_years.edit', compact('fiscalYear'));
    }

    /**
     * تحديث سنة مالية.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\FiscalYear  $fiscalYear
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, FiscalYear $fiscalYear)
    {
        // تحقق من المدخلات
        $request->validate([
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|string|in:نشطة,غير نشطة,مؤرشفة',
        ]);

        // تحديث السنة المالية
        $fiscalYear->update([
            'name' => $request->name,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'status' => $request->status,
        ]);

        // إعادة التوجيه مع رسالة نجاح
        return redirect()->route('fiscal_years.index')->with('success', 'تم تحديث السنة المالية بنجاح');
    }

    /**
     * حذف سنة مالية.
     *
     * @param  \App\Models\FiscalYear  $fiscalYear
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(FiscalYear $fiscalYear)
    {
        // حذف السنة المالية
        $fiscalYear->delete();

        // إعادة التوجيه مع رسالة نجاح
        return redirect()->route('fiscal_years.index')->with('success', 'تم حذف السنة المالية بنجاح');
    }
}

