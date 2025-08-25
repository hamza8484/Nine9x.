<?php

namespace App\Http\Controllers;

use App\SupplierTransaction;
use App\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    // عرض جميع الموردين
    public function index()
    {
        // جلب جميع الموردين من قاعدة البيانات
        $suppliers = Supplier::all();
        return view('suppliers.suppliers', compact('suppliers')); // عرض الموردين في الصفحة
    }

    // عرض نموذج إضافة مورد جديد
    public function create()
    {
        // عرض النموذج لإضافة مورد جديد
        return view('suppliers.create');
    }

    // حفظ المورد الجديد في قاعدة البيانات
    public function store(Request $request)
    {
        // التحقق من صحة البيانات المدخلة
        $request->validate([
            'sup_name' => 'required|string|max:255',  // اسم المورد
            'sup_tax_number' => 'required|string|max:50',  // الرقم الضريبي
            'sup_phone' => 'nullable|string|max:20',  // الهاتف
            'sup_mobile' => 'nullable|string|max:20',  // الجوال
            'sup_commercial_record' => 'nullable|string|max:255',  // السجل التجاري
            'sup_balance' => 'nullable|numeric',  // الرصيد
            'sup_address' => 'nullable|string',  // العنوان
            'sup_notes' => 'nullable|string',  // الملاحظات
        ]);

        // حفظ المورد في قاعدة البيانات
        Supplier::create([
            'sup_name' => $request->sup_name,
            'sup_tax_number' => $request->sup_tax_number,
            'sup_phone' => $request->sup_phone,
            'sup_mobile' => $request->sup_mobile,
            'sup_commercial_record' => $request->sup_commercial_record,
            'sup_balance' => $request->sup_balance,
            'sup_address' => $request->sup_address,
            'sup_notes' => $request->sup_notes,
            'user_id' => auth()->user()->id,
        ]);

        // إعادة التوجيه إلى صفحة الموردين بعد الحفظ
        return redirect()->route('suppliers.index')->with('add', 'تم إضافة المورد بنجاح!');
    }

    // عرض تفاصيل المورد
    public function show($id)
    {
        // جلب المورد بناءً على الـ id
        $supplier = Supplier::findOrFail($id);
        return view('suppliers.show', compact('supplier')); // عرض تفاصيل المورد
    }

    // عرض نموذج تعديل المورد
    public function edit($id)
    {
        // جلب المورد بناءً على الـ id
        $supplier = Supplier::findOrFail($id);
        return view('suppliers.edit', compact('supplier')); // عرض نموذج تعديل المورد
    }

    // تحديث بيانات المورد
    public function update(Request $request, $id)
    {
        // التحقق من صحة البيانات المدخلة
        $request->validate([
            'sup_name' => 'required|string|max:255',  // اسم المورد
            'sup_tax_number' => 'required|string|max:50',  // الرقم الضريبي
            'sup_phone' => 'nullable|string|max:20',  // الهاتف
            'sup_mobile' => 'nullable|string|max:20',  // الجوال
            'sup_commercial_record' => 'nullable|string|max:255',  // السجل التجاري
            'sup_balance' => 'nullable|numeric',  // الرصيد
            'sup_address' => 'nullable|string',  // العنوان
            'sup_notes' => 'nullable|string',  // الملاحظات
        ]);

        // جلب المورد بناءً على الـ id
        $supplier = Supplier::findOrFail($id);

        // تحديث المورد
        $supplier->update([
            'sup_name' => $request->sup_name,
            'sup_tax_number' => $request->sup_tax_number,
            'sup_phone' => $request->sup_phone,
            'sup_mobile' => $request->sup_mobile,
            'sup_commercial_record' => $request->sup_commercial_record,
            'sup_balance' => $request->sup_balance,
            'sup_address' => $request->sup_address,
            'sup_notes' => $request->sup_notes,
        ]);

        // إعادة التوجيه إلى صفحة الموردين بعد التحديث
        return redirect()->route('suppliers.index')->with('edit', 'تم تحديث المورد بنجاح!');
    }

    // حذف مورد
    public function destroy($id)
    {
        // جلب المورد بناءً على الـ id
        $supplier = Supplier::findOrFail($id);

        // حذف المورد
        $supplier->delete();

        // إعادة التوجيه إلى صفحة الموردين بعد الحذف
        return redirect()->route('suppliers.index')->with('delete', 'تم حذف المورد بنجاح!');
    }

    public function print($id)
    {
        try {
            // جلب بيانات المورد من قاعدة البيانات
            $supplier = Supplier::findOrFail($id);
            
            // إرجاع البيانات بصيغة JSON
            return response()->json([
                'sup_name' => $supplier->sup_name,
                'sup_tax_number' => $supplier->sup_tax_number,
                'sup_phone' => $supplier->sup_phone,
                'sup_mobile' => $supplier->sup_mobile,
                'sup_commercial_record' => $supplier->sup_commercial_record,
                'sup_balance' => $supplier->sup_balance,
                'sup_address' => $supplier->sup_address,
                'sup_notes' => $supplier->sup_notes,
            ]);
        } catch (\Exception $e) {
            // في حالة حدوث خطأ أثناء جلب البيانات
            return response()->json(['error' => 'حدث خطأ أثناء جلب بيانات المورد!'], 500);
        }
    }


    public function printAll()
    {
        $suppliers = Supplier::all();
        return response()->json(['suppliers' => $suppliers]);
    }

    // عرض كشف الحساب للمورد
    public function statement($id)
    {
        // جلب المورد بناءً على الـ id
        $supplier = Supplier::findOrFail($id);

        // جلب رصيد المورد الحالي
        $balance = $supplier->sup_balance;

        // جلب الحركات المالية للمورد
        $transactions = SupplierTransaction::where('supplier_id', $id)->get();

        // عرض كشف الحساب للمورد
        return view('suppliers.statement', compact('supplier', 'transactions', 'balance'));
    }

    public function showTransactions($id)
    {
        // جلب بيانات المورد
        $supplier = Supplier::findOrFail($id);
        
        // جلب جميع الحركات المالية للمورد
        $transactions = SupplierTransaction::where('supplier_id', $id)
                                           ->with('cashbox')  // لتحميل بيانات الخزنة المرتبطة بالسند
                                           ->get();

        // حساب الرصيد الحالي للمورد
        $balance = $supplier->sup_balance;

        return view('suppliers.transactions', compact('supplier', 'transactions', 'balance'));
    }
}
