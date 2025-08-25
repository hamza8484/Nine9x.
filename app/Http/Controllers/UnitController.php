<?php

namespace App\Http\Controllers;

use App\Unit;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    public function index()
    {
        $units = Unit::all(); // جلب جميع الوحدات من قاعدة البيانات
        return view('units.units', compact('units')); // تمرير المتغير 'units' إلى الـ Blade
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $request->validate([
            'unit_name' => 'required|string|max:255|unique:units', // التأكد من أن اسم الوحدة فريد
        ], [
            'unit_name.required' => 'ادخـل اســم الوحدة',
            'unit_name.unique' => 'اســم الوحدة مسجـل مـن قبـل',
        ]);

        $unit = new Unit;
        $unit->unit_name = $request->unit_name;
        $unit->user_id = auth()->user()->id;  // استخدام معرف المستخدم الحالي
        $unit->save();

        return redirect()->route('units.index')->with('Add', 'تم إضافة الوحدة بنجاح!');
    }

    public function show(Unit $unit)
    {
        //
    }

    public function edit(Unit $unit)
    {
        //
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'unit_name' => 'required|string|max:255|unique:units,unit_name,' . $id, // التأكد من أن اسم الوحدة فريد ولكن مع استثناء الـ id الحالي
        ]);

        // العثور على الوحدة باستخدام الـ id
        $unit = Unit::findOrFail($id);

        // تحديث البيانات
        $unit->update([
            'unit_name' => $request->unit_name,
        ]);

        return redirect()->route('units.index')->with('Update', 'تم تحديث الوحدة بنجاح!');
    }

    public function destroy($id)
    {
        $unit = Unit::findOrFail($id);
        $unit->delete();

        session()->flash('Delete', 'تم حذف الوحدة بنجاح');
        return redirect()->route('units.index');
    }
}

