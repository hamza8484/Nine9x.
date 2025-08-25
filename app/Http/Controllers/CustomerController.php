<?php


namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade as PDF;
use App\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    // عرض جميع العملاء
    public function index()
    {
        $customers = Customer::all();
        return view('customers.customers', compact('customers'));
    }

    // عرض نموذج إضافة عميل جديد
    public function create()
    {
        return view('customers.create');
    }

    // حفظ العميل الجديد في قاعدة البيانات
    public function store(Request $request)
    {
        // التحقق من صحة البيانات
        $request->validate([
            'cus_name' => 'required|string|max:255',
            'cus_tax_number' => 'required|string|max:255',
            'cus_phone' => 'nullable|string|max:20',
            'cus_mobile' => 'nullable|string|max:20',
            'cus_commercial_record' => 'nullable|string|max:255',
            'cus_balance' => 'required|numeric',
            'cus_address' => 'nullable|string',
            'cus_notes' => 'nullable|string',
            'cus_maile' =>'nullable|string',
        ]);

        // حفظ العميل في قاعدة البيانات
        Customer::create([
            'cus_name' => $request->cus_name,
            'cus_tax_number' => $request->cus_tax_number,
            'cus_phone' => $request->cus_phone,
            'cus_mobile' => $request->cus_mobile,
            'cus_maile' =>  $request->cus_maile,
            'cus_commercial_record' => $request->cus_commercial_record,
            'cus_balance' => $request->cus_balance,
            'cus_address' => $request->cus_address,
            'cus_notes' => $request->cus_notes,
            'user_id' => auth()->user()->id,
        ]);

        // إعادة التوجيه إلى صفحة العملاء مع رسالة نجاح
        return redirect()->route('customers.index')->with('Add', 'تم إضافة العميل بنجاح!');
    }

    // عرض تفاصيل العميل
    // في الـ Controller (CustomerController.php)
    public function show($id)
    {
        $customer = Customer::findOrFail($id);
        return view('customers.show', compact('customer'));
    }


    // عرض نموذج تعديل العميل
    public function edit($id)
    {
        $customer = Customer::findOrFail($id);
        return view('customers.edit', compact('customer'));
    }

    // تحديث بيانات العميل
    // تحديث بيانات العميل
    public function update(Request $request, $id)
    {
        // التحقق من صحة البيانات
        $request->validate([
            'cus_name' => 'required|string|max:255',
            'cus_tax_number' => 'required|string|max:255',
            'cus_phone' => 'nullable|string|max:20',
            'cus_mobile' => 'nullable|string|max:20',
            'cus_commercial_record' => 'nullable|string|max:255',
            'cus_balance' => 'required|numeric',
            'cus_address' => 'nullable|string',
            'cus_notes' => 'nullable|string',
            'cus_maile' => 'nullable|string',
        ]);

        // العثور على العميل وتحديثه
        $customer = Customer::findOrFail($id);
        $customer->update([
            'cus_name' => $request->cus_name,
            'cus_tax_number' => $request->cus_tax_number,
            'cus_phone' => $request->cus_phone,
            'cus_mobile' => $request->cus_mobile,
            'cus_commercial_record' => $request->cus_commercial_record,
            'cus_balance' => $request->cus_balance,
            'cus_address' => $request->cus_address,
            'cus_notes' => $request->cus_notes,
            'cus_maile' => $request->cus_maile,
            'user_id' => auth()->user()->id,  // ربط العميل بالمستخدم الحالي في حالة التحديث
        ]);

        // إعادة التوجيه إلى صفحة العملاء مع رسالة نجاح
        return redirect()->route('customers.index')->with('edit', 'تم تحديث العميل بنجاح!');
    }


    // حذف العميل
    public function destroy($id)
    {
        // العثور على العميل وحذفه
        $customer = Customer::findOrFail($id);
        $customer->delete();

        // إعادة التوجيه إلى صفحة العملاء مع رسالة نجاح
        return redirect()->route('customers.index')->with('delete', 'تم حذف العميل بنجاح!');
    }

    
    public function print($id) {
        // جلب بيانات العميل من قاعدة البيانات
        $customer = Customer::findOrFail($id);
    
        // إرجاع البيانات بصيغة JSON لتستخدم في AJAX
        return response()->json([
            'cus_name' => $customer->cus_name,
            'cus_tax_number' => $customer->cus_tax_number,
            'cus_phone' => $customer->cus_phone,
            'cus_mobile' => $customer->cus_mobile,
            'cus_commercial_record' => $customer->cus_commercial_record,
            'cus_balance' => $customer->cus_balance,
            'cus_address' => $customer->cus_address,
            'cus_notes' => $customer->cus_notes,
            'cus_maile' =>  $customer->cus_maile,
        ]);
    }

    public function printAll()
    {
        $customers = Customer::all();
        return response()->json(['customers' => $customers]);
    }

    public function showTransactions($id)
        {
            // جلب بيانات العميل
            $customer = Customer::findOrFail($id);
            
            // جلب جميع السندات الخاصة بالعميل
            $transactions = ClientTransaction::where('client_id', $id)->with('cashbox')->get();

            // حساب الرصيد الحالي للعميل إذا لزم الأمر
            $balance = $customer->cus_balance;

            return view('customers.transactions', compact('customer', 'transactions', 'balance'));
        }

   
}
