<?php

namespace App\Http\Controllers;

use App\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // تأكد من استخدام Auth

class CompanyController extends Controller
{
    /**
     * عرض قائمة الشركات للمستخدم الحالي.
     */
    public function index()
    {
        // جلب الشركات المرتبطة بالمستخدم الحالي
        $company = Company::where('user_id', Auth::id())->first(); 
        return view('company.company', compact('company'));
    }

    /**
     * تخزين شركة جديدة.
     */
    public function store(Request $request)
    {
        $request->validate([
            'company_name' => 'required|string|max:255',
            'tax_number' => 'required|string|max:255',
            'commercial_register' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'phone' => 'required|string|max:255',
            'mobile' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'notes' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // تخزين الشركة مع بيانات المستخدم الحالي
        $company = Company::create(array_merge($request->only([
            'company_name', 'tax_number', 'commercial_register', 'email', 
            'phone', 'mobile', 'address', 'notes'
        ]), ['user_id' => Auth::id()]));

        // حفظ الشعار إذا تم تحميله
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('logos', 'public');
            $company->update(['logo' => $logoPath]);
        }

        return redirect()->route('company.index')->with('success', 'تم إضافة بيانات الشركة بنجاح');
    }

    /**
     * عرض الشركة المحددة.
     */
    public function show($id) 
    {
        $company = Company::where('user_id', Auth::id())->findOrFail($id);  // التحقق من أن الشركة تخص المستخدم الحالي
        return view('company.company', compact('company')); 
    }

    /**
     * تحديث بيانات الشركة.
     */
    public function update(Request $request, Company $company)
    {
        // التأكد من أن الشركة تخص المستخدم الحالي
        if ($company->user_id !== Auth::id()) {
            return redirect()->route('company.index')->with('error', 'لا يمكنك تعديل بيانات شركة لم تقم بإضافتها');
        }

        $request->validate([
            'company_name' => 'required|string|max:255',
            'tax_number' => 'required|string|max:255',
            'commercial_register' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'phone' => 'required|string|max:255',
            'mobile' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'notes' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // تحديث الشركة
        $company->update($request->only([
            'company_name', 'tax_number', 'commercial_register', 'email', 
            'phone', 'mobile', 'address', 'notes'
        ]));

        // حفظ الشعار الجديد إذا تم تحميله
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('logos', 'public');
            $company->update(['logo' => $logoPath]);
        }

        return redirect()->route('company.index')->with('success', 'تم تعديل بيانات الشركة بنجاح');
    }

    /**
     * حذف الشركة.
     */
    public function destroy(Company $company)
    {
        // التأكد من أن الشركة تخص المستخدم الحالي
        if ($company->user_id !== Auth::id()) {
            return redirect()->route('company.index')->with('error', 'لا يمكنك حذف شركة لم تقم بإضافتها');
        }

        // حذف الشركة
        $company->delete();

        return redirect()->route('company.index')->with('success', 'تم حذف بيانات الشركة بنجاح');
    }
}
