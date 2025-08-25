<?php

namespace App\Http\Controllers;

use App\Branch;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $branchs = Branch::with('user')->get(); // تم تغيير المتغير من branchs إلى branches
        return view('branches.branches', compact('branchs')); // تمرير المتغير 'branches' بشكل صحيح
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // عرض صفحة إضافة الفرع
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // التحقق من البيانات المدخلة
        $request->validate([
            'bra_name' => 'required|string|max:255',
            'bra_type' => 'required|string|in:رئيسي,فرعي',
            'bra_address' => 'required|string|max:255',
            'bra_phone' => 'required|string|max:20',
            'bra_manager' => 'nullable|string|max:255',
            'bra_manager_phone' => 'nullable|string|max:20',
            'is_active' => 'required|boolean',
            'bra_telephon' => 'nullable|string|max:20', // تأكد من إضافة bra_telephon إلى التحقق
            'branch_notes' => 'nullable|string',
        ]);

        // إنشاء فرع جديد
        Branch::create([
            'bra_name' => $request->bra_name,
            'bra_type' => $request->bra_type,
            'bra_address' => $request->bra_address,
            'bra_phone' => $request->bra_phone,
            'bra_manager' => $request->bra_manager,
            'bra_manager_phone' => $request->bra_manager_phone,
            'is_active' => $request->is_active,
            'user_id' => auth()->id(), // استخدام user_id للمستخدم الذي قام بتسجيل الدخول
            'bra_telephon' => $request->bra_telephon,
            'branch_notes' => $request->branch_notes,
        ]);

        return redirect()->route('branches.index')->with('success', 'تم إضافة الفرع بنجاح');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Branch  $branch
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $branch = Branch::findOrFail($id); // تأكد من أنه يتم جلب الفرع بشكل صحيح
        return view('branches.edit', compact('branch'));  // تمرير المتغير 'branch' بشكل صحيح
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Branch  $branch
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Branch $branch)
    {
        // التحقق من البيانات المدخلة
        $request->validate([
            'bra_name' => 'required|string|max:255',
            'bra_type' => 'required|string|in:رئيسي,فرعي',
            'bra_address' => 'required|string|max:255',
            'bra_phone' => 'required|string|max:20',
            'bra_manager' => 'nullable|string|max:255',
            'bra_manager_phone' => 'nullable|string|max:20',
            'is_active' => 'required|boolean',
            'bra_telephon' => 'nullable|string|max:20', // تأكد من إضافة bra_telephon إلى التحقق
            'branch_notes' => 'nullable|string',
        ]);

        // تحديث بيانات الفرع
        $branch->update([
            'bra_name' => $request->bra_name,
            'bra_type' => $request->bra_type,
            'bra_address' => $request->bra_address,
            'bra_phone' => $request->bra_phone,
            'bra_manager' => $request->bra_manager,
            'bra_manager_phone' => $request->bra_manager_phone,
            'is_active' => $request->is_active,
            'user_id' => auth()->id(), // تأكد من استخدام المستخدم المسجل دخوله
            'bra_telephon' => $request->bra_telephon,
            'branch_notes' => $request->branch_notes,
        ]);

        return redirect()->route('branches.index')->with('success', 'تم تعديل الفرع بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Branch  $branch
     * @return \Illuminate\Http\Response
     */
    public function destroy(Branch $branch)
    {
        // حذف الفرع
    }
}
