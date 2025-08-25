<?php

namespace App\Http\Controllers;

//use App\Expense;
use App\ECategory;
use App\Tax;
use Illuminate\Http\Request;

class ECategoryController extends Controller
{
    /**
     * Display a listing of the expenses.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $e_categories = ECategory::all();

        return view('e_categories.e_categories', compact('e_categories'));
    }

    /**
     * Show the form for creating a new expense.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // جلب جميع الفئات والضرائب لإظهارها في النموذج
        $e_categories = ECategory::all();
        $taxes = Tax::all();

        return view('e_categories.create', compact('e_categories', 'taxes'));
    }

    /**
     * Store a newly created expense in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // التحقق من البيانات المدخلة
        $request->validate([
            'cat_name' => 'required|string|max:50',
            'description' => 'required|string|max:255',
        ]);

        // حفظ المصروف
        ECategory::create([
            'cat_name' => $request->cat_name,
            'description' => $request->description,
            'user_id' => auth()->id(),
            'created_by' => auth()->id(), 
        ]);

        return redirect()->route('e_categories.index')->with('success', 'ECategory created successfully');
    }

    /**
     * Show the form for editing the specified expense.
     *
     * @param  \App\ECategory  $e_categories
     * @return \Illuminate\Http\Response
     */
    public function edit(ECategory $e_categories)
    {
        $e_categories = ECategory::all();
        $taxes = Tax::all();
        return view('e_categories.edit', compact('expense', 'e_categories', 'taxes'));
    }

    /**
     * Update the specified expense in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ECategory  $e_categories
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ECategory $e_categories)
    {
        $request->validate([
            'cat_name' => 'required|string|max:50',
            'description' => 'required|string|max:255',
        ]);

        $expense->update([
            'cat_name' => $request->cat_name,
            'description' => $request->description,
            'user_id' => auth()->id(),
            'created_by' => auth()->id(), 
        ]);

        return redirect()->route('e_categories.index')->with('success', 'Expense updated successfully');
    }

    /**
     * Remove the specified expense from storage.
     *
     * @param  \App\Expense  $expense
     * @return \Illuminate\Http\Response
     */
    public function destroy(ECategory $e_categories)
    {
        $expense->delete();
        return redirect()->route('e_categories.index')->with('success', 'Expense deleted successfully');
    }
}
