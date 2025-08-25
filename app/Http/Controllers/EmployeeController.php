<?php

namespace App\Http\Controllers;

use App\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    /**
     * عرض قائمة الموظفين.
     */
    public function index()
    {
        $employees = Employee::all();
        
        return view('employees.employees', compact('employees'));
    }

    /**
     * عرض نموذج إضافة موظف جديد.
     */
    public function create()
    {
        // الحصول على أكبر رقم موظف حالياً
        $lastEmployee = Employee::orderBy('id', 'desc')->first();

        // إذا كانت هناك بيانات سابقة، نأخذ الرقم الأخير ونضيف 1 له
        $newEmployeeNumber = $lastEmployee ? 'EMP-' . str_pad((int) substr($lastEmployee->emp_number, 4) + 1, 4, '0', STR_PAD_LEFT) : 'EMP-1001';

        // تمرير المتغير إلى العرض
        return view('employees.create_employee', compact('newEmployeeNumber'));
    }



    /**
     * حفظ موظف جديد في قاعدة البيانات.
     */
   // مثال لتعديل دالة `store` لرفع الصور:

public function store(Request $request)
{
    //dd($request->all());
    // التحقق من صحة البيانات
    $request->validate([
        'emp_name' => 'required|unique:employees',
        'emp_id_number' => 'required|unique:employees',
        'emp_salary' => 'required|numeric',
        'emp_allowance' => 'nullable|numeric',
        'emp_email' => 'required|email|unique:employees',
        'emp_department' => 'required',
        'emp_position' => 'required',
        'emp_status' => 'required|in:active,inactive',
        // تحقق من الصور المرفوعة
        'emp_image' => 'nullable|image',
        'emp_id_image' => 'nullable|image',
        'emp_contract_image' => 'nullable|image',
        'emp_status' => 'required|in:active,inactive',
    ]);

    // تخزين الصور (إذا كانت موجودة)
    $emp_image = $request->hasFile('emp_image') ? $request->file('emp_image')->store('employees/images') : null;
    $emp_id_image = $request->hasFile('emp_id_image') ? $request->file('emp_id_image')->store('employees/id_images') : null;
    $emp_contract_image = $request->hasFile('emp_contract_image') ? $request->file('emp_contract_image')->store('employees/contract_images') : null;

    // إنشاء موظف جديد
    Employee::create([
        'emp_name' => $request->emp_name,
        'emp_number'=>$request->emp_number,
        'emp_id_number' => $request->emp_id_number,
        'emp_age' => $request->emp_age,
        'emp_salary' => $request->emp_salary,
        'emp_allowance' => $request->emp_allowance,
        'emp_birth_date' => $request->emp_birth_date,
        'emp_hire_date' => $request->emp_hire_date,
        'emp_phone' => $request->emp_phone,
        'emp_mobile' => $request->emp_mobile,
        'emp_email' => $request->emp_email,
        'emp_department' => $request->emp_department,
        'emp_image' => $emp_image,
        'emp_position' => $request->emp_position,
        'emp_id_image' => $emp_id_image,
        'emp_contract_image' => $emp_contract_image,
        'emp_status' => $request->emp_status,
        'emp_notes' => $request->emp_notes,
        'user_id' => auth()->id(),
    ]);

    return redirect()->route('employees.index')->with('Add', 'Employee created successfully.');
}


    /**
     * عرض تفاصيل موظف معين.
     */
    public function show(Employee $employee)
    {
        return view('employees.show', compact('employee'));
    }

    /**
     * عرض نموذج تعديل بيانات موظف معين.
     */
    public function edit($id)
    {
        // جلب الموظف بناءً على الـ ID
        $employee = Employee::findOrFail($id);

        // إرسال الموظف إلى الـ View
        return view('employees.edit', compact('employee'));
    }



    /**
     * تحديث بيانات موظف معين.
     */
    public function update(Request $request, Employee $employee ,$id)
    {
        // التحقق من صحة البيانات
        $request->validate([
            'emp_name' => 'required|unique:employees,emp_name,' . $employee->id,
            'emp_id_number' => 'required|unique:employees,emp_id_number,' . $employee->id,
            'emp_salary' => 'required|numeric',
            'emp_allowance' => 'nullable|numeric',
            'emp_email' => 'required|email|unique:employees,emp_email,' . $employee->id,
            'emp_department' => 'required',
            'emp_position' => 'required',
            'emp_status' => 'required|in:active,inactive',
        ]);

        // تحديث بيانات الموظف
        $employee->update([
            'emp_name' => $request->emp_name,
            'emp_number'=>$request->emp_number,
            'emp_id_number' => $request->emp_id_number,
            'emp_age' => $request->emp_age,
            'emp_salary' => $request->emp_salary,
            'emp_allowance' => $request->emp_allowance,
            'emp_birth_date' => $request->emp_birth_date,
            'emp_hire_date' => $request->emp_hire_date,
            'emp_phone' => $request->emp_phone,
            'emp_mobile' => $request->emp_mobile,
            'emp_email' => $request->emp_email,
            'emp_department' => $request->emp_department,
            'emp_image' => $request->emp_image,
            'emp_position' => $request->emp_position,
            'emp_id_image' => $request->emp_id_image,
            'emp_contract_image' => $request->emp_contract_image,
            'emp_status' => $request->emp_status,
            'emp_notes' => $request->emp_notes,
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('employees.index')->with('Update', 'Employee updated successfully.');
    }

    /**
     * حذف موظف معين.
     */
    public function destroy(Employee $employee)
    {
        $employee->delete();
        return redirect()->route('employees.index')->with('Delete', 'Employee deleted successfully.');
    }

    // EmployeeController.php
    public function print($id)
    {
        // جلب بيانات الموظف بناءً على الـ ID
        $employee = Employee::findOrFail($id);

        // عرض بيانات الموظف في صفحة جديدة
        return view('employees.print_employee', compact('employee'));
    }

}
