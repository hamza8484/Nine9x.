<?php

namespace App\Http\Controllers;

use App\Store;
use App\Branch; // إضافة الفئة Branch
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StoreController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $branches = Branch::all();
        $stores = Store::all();
        
        return view('stores.stores', compact('stores','branches'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $branches = Branch::all(); // جلب جميع الفروع
        return view('stores.create', compact('branches'));
    }

    public function edit($id)
    {
       //
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
            'store_name' => 'required|string|max:255',
            'store_location' => 'nullable|string|max:255',
            'total_stock' => 'required|integer',
            'inventory_value' => 'required|numeric',
            'status' => 'required|string',
            'store_notes' => 'nullable|string',
            'branch_id' => 'required|exists:branches,id',  // التحقق من صحة الفرع المختار
        ]);
    
        // حفظ المخزن مع ربطه بالفرع والمستخدم الحالي
        $store = Store::create([
            'store_name' => $request->store_name,
            'store_location' => $request->store_location,
            'total_stock' => $request->total_stock,
            'inventory_value' => $request->inventory_value,
            'status' => $request->status,
            'store_notes' => $request->store_notes,
            'branch_id' => $request->branch_id,  // إضافة الفرع
            'user_id' => auth()->id(),  // إضافة المستخدم الحالي
        ]);
    
        // إعادة التوجيه مع رسالة النجاح
        return redirect()->route('stores.index')->with('Add', 'تم إضافة المخزن بنجاح!');
    }
    

    // تحديث المخزن
    public function update(Request $request, $id)
    {
        $store = Store::findOrFail($id);
    
        // التحقق إذا كان المستخدم هو من قام بإنشاء المخزن
        if ($store->user_id !== auth()->id()) {
            return redirect()->route('stores.index')->with('error', 'لا يمكنك تعديل هذا المخزن');
        }
    
        // التحقق من البيانات المدخلة
        $validated = $request->validate([
            'store_name' => 'required|string|max:255',
            'store_location' => 'nullable|string|max:255',
            'branch_id' => 'required|exists:branches,id',
            'total_stock' => 'required|integer|min:0',
            'inventory_value' => 'required|numeric|min:0',
            'status' => 'required|in:active,inactive',
            'store_notes' => 'nullable|string',
        ]);
    
        // تحديث المخزن
        $store->update($validated);
    
        return redirect()->route('stores.index')->with('Update', 'تم تحديث المخزن بنجاح!');
    }
    


    // حذف المخزن
    public function destroy($id)
    {
        $store = Store::findOrFail($id);
        $store->delete();

        return redirect()->route('stores.index')->with('Delete', 'تم حذف المخزن بنجاح!');
    }

    // عرض تفاصيل المخزن
    public function show($id)
    {
        $store = Store::findOrFail($id);
        return view('stores.show', compact('store'));
    }

    // عرض جميع المخازن مع تفاصيل الجرد
    public function inventory()
    {
        // استرجاع المخازن مع فروعها ومنتجاتها
        $stores = Store::with(['products', 'branch'])->get(); // استخدام العلاقة مع الفرع
        return view('stores.inventory', compact('stores'));
    }

    // عرض صفحة تعديل جرد المنتج داخل مخزن
    public function editInventory($storeId, $productId)
    {
        // العثور على المخزن
        $store = Store::findOrFail($storeId);
        // العثور على المنتج
        $product = Product::findOrFail($productId);
        return view('stores.inventory_edit', compact('store', 'product'));
    }

    // تحديث الجرد للمنتج
    public function updateInventory(Request $request, $storeId, $productId)
    {
        // التحقق من صحة البيانات
        $request->validate([
            'product_quantity' => 'required|integer|min:0',
            'product_sale_price' => 'required|numeric|min:0',
        ]);

        // العثور على المنتج وتحديث الكمية والسعر
        $product = Product::findOrFail($productId);
        $product->product_quantity = $request->product_quantity;
        $product->product_sale_price = $request->product_sale_price;
        $product->save();

        // تحديث الكمية الإجمالية في المخزن
        $store = Store::findOrFail($storeId);
        $store->total_stock = $store->products->sum('product_quantity');
        $store->save();

        return redirect()->route('inventory.index')->with('Update', 'تم تحديث الجرد للمنتج بنجاح!');
    }
}
