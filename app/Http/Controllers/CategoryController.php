<?php

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // جلب جميع الفئات
        $categories = Category::all();
        return view('categories.categories', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // عرض نموذج إنشاء الفئة
        return view('categories.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // التحقق من صحة البيانات
        $request->validate([
            'c_name' => 'required|string|max:255|unique:categories,c_name',
            'c_description' => 'nullable|string',
        ], [
            'c_name.required' => 'ادخل اسم المجموعة',
            'c_name.unique' => 'اسم المجموعة مسجل مسبقاً',
            'c_description.required' => 'ادخل الوصف',
        ]);

        // إنشاء الفئة
        $category = new Category();
        $category->c_name = $request->c_name;
        $category->c_description = $request->c_description;
        $category->user_id = auth()->id();  // ربط الفئة بالمستخدم الذي قام بإنشائها
        $category->save();

        session()->flash('Add', 'تم إضافة المجموعة بنجاح');
        return redirect()->route('categories.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // العثور على الفئة للتعديل
        $category = Category::findOrFail($id);
        return view('categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // التحقق من صحة البيانات
        $request->validate([
            'c_name' => 'required|string|max:255',
            'c_description' => 'required|string|max:255',
        ]);

        // العثور على الفئة باستخدام الـ id
        $category = Category::findOrFail($id);

        // تحديث البيانات
        $category->update([
            'c_name' => $request->c_name,
            'c_description' => $request->c_description,
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('categories.index')->with('Update', 'تم تحديث المجموعة بنجاح!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // العثور على الفئة والحذف
        $category = Category::findOrFail($id);
        $category->delete();

        session()->flash('Delete', 'تم حذف المجموعة بنجاح');
        return redirect()->route('categories.index');
    }
}

