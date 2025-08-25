<?php

namespace App\Http\Controllers;

use App\AccountType;
use Illuminate\Http\Request;

class AccountTypeController extends Controller
{
    public function index()
    {
        $types = AccountType::all();
        return view('account_types.index', compact('types'));
    }

    public function create()
    {
        return view('account_types.create');
    }

   public function store(Request $request)
    {
        
        // التحقق من البيانات المدخلة
        $request->validate([
            'name' => 'required',
            'code' => 'required|unique:account_types,code',
            'is_active' => 'required|boolean',
        ]);

        try {
            // إضافة الحقل is_active عند الإنشاء
            AccountType::create([
                'name' => $request->name,
                'code' => $request->code,
                'description' => $request->description,
                'is_active' => $request->has('is_active') ? 1 : 0, // تعيين القيمة حسب ما إذا تم تحديد الحقل أم لا
            ]);
        } catch (\Exception $e) {
            // سجل الخطأ
            Log::error('Error saving account type', ['error' => $e->getMessage()]);
            return redirect()->back()->withErrors(['error' => 'حدث خطأ أثناء الحفظ']);
        }

        return redirect()->route('account_types.index')->with('success', 'تمت إضافة النوع بنجاح');
    }



    public function edit($id)
    {
        // جلب نوع الحساب بناءً على الـ id
        $type = AccountType::findOrFail($id);

        // تمرير البيانات إلى صفحة التعديل
        return view('account_types.edit', compact('type'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50',
            'description' => 'nullable|string|max:1000',
            'is_active' => 'required|boolean', // تأكيد أن الحقل is_active موجود
        ]);

        $type = AccountType::findOrFail($id);

        // تحديث الحقل is_active في البيانات
        $type->update([
            'name' => $request->name,
            'code' => $request->code,
            'description' => $request->description,
            'is_active' => $request->has('is_active') ? 1 : 0, // تعيين القيمة حسب ما إذا تم تحديد الحقل أم لا
        ]);

        return redirect()->route('account_types.index')->with('success', 'تم تعديل نوع الحساب بنجاح');
    }


     // دالة حذف نوع الحساب
     public function destroy($id)
    {
        // جلب نوع الحساب بناءً على المعرف
        $accountType = AccountType::findOrFail($id);

        // حذف الحسابات المرتبطة بنوع الحساب
        $accountType->accounts()->delete();

        // حذف نوع الحساب
        $accountType->delete();

        // إعادة التوجيه مع رسالة نجاح
        return redirect()->route('account_types.index')->with('success', 'تم حذف نوع الحساب بنجاح!');
    }


}
