<?php

namespace App\Http\Controllers;

use App\SalaryPayment;
use App\Employee;
use App\Cashbox;
use App\Account;
use App\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SalaryPaymentController extends Controller
{
    // عرض جميع عمليات تسليم الرواتب
    public function index()
    {
        $salaryPayments = SalaryPayment::with('employee')->get(); 
        $employees = Employee::all();
        $cashboxes = Cashbox::all();
        $accounts = Account::all();
        $branches = Branch::all();
        return view('salary_payments.salary_payments', compact('salaryPayments','employees', 'cashboxes', 'accounts', 'branches'));
    }

    // عرض الصفحة لإنشاء عملية تسليم راتب جديدة
    public function create()
    {
        $employees = Employee::all(); 
        $cashboxes = Cashbox::all(); 
        $accounts = Account::all(); 
        $branches = Branch::all(); 
        return view('salary_payments.create', compact('employees', 'cashboxes', 'accounts', 'branches'));
    }

    // تخزين بيانات تسليم الراتب
    public function store(Request $request)
    {
        // التحقق من صحة البيانات المدخلة
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'payment_date' => 'required|date',
            'amount' => 'required|numeric',
            'gross_salary' => 'required|numeric',
            'net_amount' => 'required|numeric',
            'salary_month' => 'required|in:January,February,March,April,May,June,July,August,September,October,November,December',
            'salary_year' => 'required|integer',
        ]);

        // التحقق مما إذا كان الموظف قد استلم راتباً كاملاً في نفس الشهر والسنة
        $existingFullPayment = SalaryPayment::where('employee_id', $request->employee_id)
                                            ->where('salary_month', $request->salary_month)
                                            ->where('salary_year', $request->salary_year)
                                            ->where('payment_status', 'paid')
                                            ->where('net_amount', $request->gross_salary)  // التأكد من دفع الراتب كامل
                                            ->first();

        if ($existingFullPayment) {
            return back()->with('error', 'تم دفع الراتب بالكامل لهذا الموظف في هذا الشهر');
        }

        // التحقق مما إذا كان الموظف قد استلم جزءاً من الراتب في هذا الشهر
        $existingPartialPayment = SalaryPayment::where('employee_id', $request->employee_id)
                                            ->where('salary_month', $request->salary_month)
                                            ->where('salary_year', $request->salary_year)
                                            ->where('payment_status', 'paid')
                                            ->where('net_amount', '<', $request->gross_salary)  // تم دفع جزء من الراتب
                                            ->first();

        if ($existingPartialPayment) {
            // إذا كان قد تم دفع جزء من الراتب، يجب أن يتم دفع الباقي فقط
            if ($existingPartialPayment->net_amount + $request->net_amount > $request->gross_salary) {
                return back()->with('error', 'المبلغ المدفوع يتجاوز الراتب الكامل.');
            }
        }

        // التحقق من رصيد الخزنة قبل حفظ العملية
        $cashbox = Cashbox::find($request->cashbox_id);
        if ($cashbox && $cashbox->cash_balance < $request->net_amount) {
            return back()->with('error', 'رصيد الخزنة غير كافٍ');
        }

        // إنشاء سجل تسليم راتب جديد
        $salaryPayment = SalaryPayment::create([
            'employee_id' => $request->employee_id,
            'cashbox_id' => $request->cashbox_id,
            'account_id' => $request->account_id,
            'branch_id' => $request->branch_id,
            'amount' => $request->amount,
            'gross_salary' => $request->gross_salary,
            'bonus' => $request->bonus ?? 0,
            'deduction' => $request->deduction ?? 0,
            'tax_deduction' => $request->tax_deduction ?? 0,
            'total_deductions' => $request->total_deductions ?? 0,
            'net_amount' => $request->net_amount,
            'payment_date' => $request->payment_date,
            'payment_status' => $request->payment_status ?? 'unpaid',
            'payment_method' => $request->payment_method,
            'payment_reference' => $request->payment_reference,
            'currency' => $request->currency ?? 'SAR',
            'payment_received_by' => $request->payment_received_by,
            'payment_notes' => $request->payment_notes,
            'payment_method_details' => $request->payment_method_details,
            'salary_month' => $request->salary_month,
            'salary_year' => $request->salary_year,
            'user_id' => auth()->id(),
        ]);

        if ($salaryPayment) {
            // خصم الراتب من الخزنة
            $cashbox->cash_balance -= $request->net_amount;
            if (!$cashbox->save()) {
                Log::error('Failed to update cashbox balance');
                return back()->with('error', 'حدث خطأ أثناء تحديث رصيد الخزنة');
            }

            // خصم المبلغ من الحساب
            $account = Account::find($request->account_id);
            if ($account) {
                $account->balance -= $request->net_amount;
                if (!$account->save()) {
                    Log::error('Failed to update account balance');
                    return back()->with('error', 'حدث خطأ أثناء تحديث رصيد الحساب');
                }
            }

            return redirect()->route('salary_payments.index')->with('success', 'تم تسليم الراتب بنجاح');
        } else {
            return back()->with('error', 'حدث خطأ أثناء حفظ البيانات.');
        }
    }




    // عرض صفحة تعديل تسليم الراتب
    public function edit($id)
    {
        $salaryPayment = SalaryPayment::findOrFail($id); 
        $employees = Employee::all(); 
        $cashboxes = Cashbox::all(); 
        $accounts = Account::all(); 
        $branches = Branch::all(); 
        return view('salary_payments.edit', compact('salaryPayment', 'employees', 'cashboxes', 'accounts', 'branches'));
    }

    // تحديث تسليم الراتب
   public function update(Request $request, $id)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'payment_date' => 'required|date',
            'amount' => 'required|numeric',
            'gross_salary' => 'required|numeric',
            'net_amount' => 'required|numeric',
            'salary_month' => 'required|in:January,February,March,April,May,June,July,August,September,October,November,December',
            'salary_year' => 'required|integer',
        ]);

        $salaryPayment = SalaryPayment::findOrFail($id);

        // التحقق مما إذا كان الموظف قد استلم راتباً في نفس الشهر والسنة
        $existingPayment = SalaryPayment::where('employee_id', $request->employee_id)
                                        ->where('salary_month', $request->salary_month)
                                        ->where('salary_year', $request->salary_year)
                                        ->where('payment_status', 'paid') // تأكد أن الراتب مدفوع
                                        ->where('id', '!=', $id)  // استثناء السجل الحالي
                                        ->first();

        if ($existingPayment) {
            return back()->with('error', 'تم دفع الراتب للموظف في هذا الشهر بالفعل');
        }

        // التحقق من رصيد الخزنة قبل التحديث
        $cashbox = Cashbox::find($request->cashbox_id);
        if ($cashbox && $cashbox->cash_balance < $request->net_amount) {
            return back()->with('error', 'رصيد الخزنة غير كافٍ');
        }

        $salaryPayment->update([
            'employee_id' => $request->employee_id,
            'cashbox_id' => $request->cashbox_id,
            'account_id' => $request->account_id,
            'branch_id' => $request->branch_id,
            'amount' => $request->amount,
            'gross_salary' => $request->gross_salary,
            'bonus' => $request->bonus ?? 0,
            'deduction' => $request->deduction ?? 0,
            'tax_deduction' => $request->tax_deduction ?? 0,
            'total_deductions' => $request->total_deductions ?? 0,
            'net_amount' => $request->net_amount,
            'payment_date' => $request->payment_date,
            'payment_status' => $request->payment_status ?? 'unpaid',
            'payment_method' => $request->payment_method,
            'payment_reference' => $request->payment_reference,
            'currency' => $request->currency ?? 'SAR',
            'payment_received_by' => $request->payment_received_by,
            'payment_notes' => $request->payment_notes,
            'payment_method_details' => $request->payment_method_details,
            'salary_month' => $request->salary_month,
            'salary_year' => $request->salary_year,
            'user_id' => auth()->id(),
        ]);

        if ($salaryPayment) {
            // خصم الراتب من الخزنة
            $cashbox->cash_balance -= $request->net_amount;
            if (!$cashbox->save()) {
                Log::error('Failed to update cashbox balance during update');
                return back()->with('error', 'حدث خطأ أثناء تحديث رصيد الخزنة');
            }

            // خصم المبلغ من الحساب
            $account = Account::find($request->account_id);
            if ($account) {
                $account->balance -= $request->net_amount;
                if (!$account->save()) {
                    Log::error('Failed to update account balance during update');
                    return back()->with('error', 'حدث خطأ أثناء تحديث رصيد الحساب');
                }
            }

            return redirect()->route('salary_payments.index')->with('success', 'تم تحديث الراتب بنجاح');
        } else {
            return back()->with('error', 'حدث خطأ أثناء تحديث البيانات');
        }
    }



    public function updatePaymentStatus($id)
    {
        // جلب معاملة الراتب
        $salaryPayment = SalaryPayment::findOrFail($id);

        // تحقق إذا كان الدفع غير مدفوع
        if ($salaryPayment->payment_status === 'unpaid') {
            // تحديث الحالة إلى مدفوع
            $salaryPayment->update([
                'payment_status' => 'paid',
                'payment_date' => now(),  // تعيين تاريخ الدفع الحالي
            ]);

            return redirect()->route('salary_payments.index')->with('success', 'تم دفع الراتب بنجاح');
        } else {
            return back()->with('error', 'تم دفع الراتب بالفعل');
        }
    }

 

   public function showDetails($id)
    {
        // جلب البيانات من جدول الدفع مع علاقة الموظف
        $payment = SalaryPayment::with('employee')->findOrFail($id);

        // تنسيق تاريخ الدفع إذا كان موجودًا
        $paymentDate = $payment->payment_date ? $payment->payment_date->format('Y-m-d') : 'لا يوجد تاريخ';

        // إرجاع الاستجابة مع جميع البيانات المطلوبة
        return response()->json([
            'employee_name' => $payment->employee->emp_name ?? 'غير معروف',
            'employee_id' => $payment->employee_id,
            'cashbox_id' => $payment->cashbox_id,
            'account_id' => $payment->account_id,
            'branch_id' => $payment->branch_id,
            'amount' => $payment->amount,
            'gross_salary' => $payment->gross_salary,
            'bonus' => $payment->bonus,
            'deduction' => $payment->deduction,
            'tax_deduction' => $payment->tax_deduction,
            'total_deductions' => $payment->total_deductions,
            'net_amount' => $payment->net_amount,
            'payment_date' => $paymentDate,
            'payment_status' => $payment->payment_status,
            'payment_method' => $payment->payment_method,
            'payment_reference' => $payment->payment_reference,
            'currency' => $payment->currency,
            'payment_received_by' => $payment->payment_received_by,
            'payment_notes' => $payment->payment_notes ?? 'لا يوجد',
            'payment_method_details' => $payment->payment_method_details,
            'salary_month' => $payment->salary_month,
            'salary_year' => $payment->salary_year,
            
        ]);
    }


}


