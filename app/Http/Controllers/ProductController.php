<?php

namespace App\Http\Controllers;

use App\Product;
use App\Store;
use App\Category;
use App\Unit;
use App\Tax;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // استرجاع البيانات من قواعد البيانات
        $stores = Store::all(); // استرجاع جميع المخازن
        $categories = Category::all(); // استرجاع جميع المجموعات
        $units = Unit::all(); // استرجاع جميع الوحدات
        $taxes = Tax::all(); // استرجاع جميع الضرائب
        $products = Product::all(); // استرجاع جميع المنتجات
        
        $lastBarcode = Product::max('product_barcode'); // الحصول على آخر باركود
        $newBarcode = $lastBarcode ? str_pad((intval($lastBarcode) + 1), 14, '0', STR_PAD_LEFT) : '0000000000001'; 

        // تأكد أن الباركود الذي تم توليده غير مكرر
        while (Product::where('product_barcode', $newBarcode)->exists()) {
            $newBarcode = str_pad((intval($newBarcode) + 1), 14, '0', STR_PAD_LEFT);
        }

        // توليد الرقم التسلسلي
        $newSerialNumber = Product::max('product_serial_number') + 1; // الحصول على أكبر رقم تسلسلي سابق وإضافة 1

        // تمرير البيانات إلى العرض
        return view('products.products', compact('stores', 'categories', 'units', 'taxes', 'products', 'newBarcode', 'newSerialNumber'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // توليد الباركود التسلسلي
        $lastBarcode = Product::max('product_barcode'); // الحصول على آخر باركود
        $newBarcode = $lastBarcode ? str_pad((intval($lastBarcode) + 1), 14, '0', STR_PAD_LEFT) : '0000000000001'; // توليد الباركود التالي

        // توليد الرقم التسلسلي
        $lastSerialNumber = Product::max('product_serial_number');
        $newSerialNumber = $lastSerialNumber ? $lastSerialNumber + 1 : 1;

        // جلب البيانات من الجداول الأخرى
        $stores = Store::all(); 
        $categories = Category::all(); 
        $units = Unit::all(); 
        $taxes = Tax::all();

        // تمرير القيم إلى الصفحة
        return view('products.create', compact('newBarcode', 'newSerialNumber', 'stores', 'categories', 'units', 'taxes'));
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
            'product_name' => 'required|string|max:255',
            'product_serial_number' => 'nullable|string|max:255',
            'product_status' => 'required|string',
            'expiry_date_status' => 'required|string',
            'product_sale_price' => 'required|numeric|min:0',
            'tax_id' => 'required|numeric|min:0',
            'product_quantity' => 'required|integer|min:1',
            'product_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'product_description' => 'nullable|string',
            'store_id' => 'required|exists:stores,id', // تحقق من وجود store_id
            'category_id' => 'required|exists:categories,id', // تحقق من وجود category_id
            'unit_id' => 'required|exists:units,id'
        ]);

        // توليد الباركود التسلسلي
        $lastBarcode = Product::max('product_barcode');
        $newBarcode = $lastBarcode ? str_pad((intval($lastBarcode) + 1), 14, '0', STR_PAD_LEFT) : '0000000000001';

        while (Product::where('product_barcode', $newBarcode)->exists()) {
            $newBarcode = str_pad((intval($newBarcode) + 1), 14, '0', STR_PAD_LEFT);
        }

        $newSerialNumber = Product::max('product_serial_number') + 1;

        // معالجة ملف الصورة
        $imagePath = null;
        if ($request->hasFile('product_image')) {
            $imagePath = $request->file('product_image')->store('products', 'public');
        }

        // استرجاع معدل الضريبة
        $tax = Tax::find($request->tax_id);
        $taxRate = $tax ? $tax->tax_rate : 0;

        // حساب المجموع بعد الضريبة
        $productTotalPrice = $request->product_sale_price * (1 + ($taxRate / 100));

        // إنشاء المنتج
        $product = new Product();
        $product->product_name = $request->product_name;
        $product->product_barcode = $newBarcode;
        $product->product_serial_number = $newSerialNumber;
        $product->product_status = $request->product_status;
        $product->expiry_date_status = $request->expiry_date_status;
        $product->product_expiry_date = $request->expiry_date_status == 'مفعل' ? $request->product_expiry_date : null;
        $product->product_sale_price = $request->product_sale_price;
        $product->product_quantity = $request->product_quantity;
        $product->product_image = $imagePath;
        $product->product_description = $request->product_description;
        $product->store_id = $request->store_id;
        $product->user_id = Auth::user()->id;
        $product->unit_id = $request->unit_id;
        $product->tax_id = $request->tax_id;
        $product->tax_rate = $taxRate;
        $product->category_id = $request->category_id;
        $product->product_total_price = $productTotalPrice;
        $product->save();

        // تحديث الكمية في المخزن
        $store = Store::findOrFail($request->store_id);
        $store->total_stock += $request->product_quantity;  // إضافة الكمية الجديدة إلى المخزن
        $store->save();

        return redirect()->route('products.index')->with('Add', 'تم إضافة المنتج بنجاح');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // جلب المنتج
        $product = Product::findOrFail($id);

        // جلب جميع البيانات المتعلقة بالمنتج
        $stores = Store::all();
        $categories = Category::all();
        $units = Unit::all();
        $taxes = Tax::all();

        // تمرير المنتج وبيانات أخرى إلى صفحة التعديل
        return view('products.edit', compact('product', 'stores', 'categories', 'units', 'taxes'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // التحقق من صحة البيانات
        $request->validate([
            'store_id' => 'required|exists:stores,id',  // تحقق من وجود store_id في جدول المخازن
            'category_id' => 'required|exists:categories,id',
            'unit_id' => 'required|exists:units,id',
            'product_name' => 'required|string|max:255',
            'product_serial_number' => 'nullable|string|max:255',
            'product_status' => 'required|string',
            'expiry_date_status' => 'required|string',
            'product_sale_price' => 'required|numeric|min:0',
            'tax_id' => 'required|numeric|min:0',
            'product_quantity' => 'required|integer|min:1',
            'product_description' => 'nullable|string',
        ]);

        $product = Product::findOrFail($id);
        $imagePath = $product->product_image;

        if ($request->hasFile('product_image')) {
            if (file_exists(public_path('images/products/' . $imagePath))) {
                unlink(public_path('images/products/' . $imagePath));
            }
            $imagePath = $request->file('product_image')->store('products', 'public');

        }

        $expiryDate = null;
        if ($request->expiry_date_status == 'مفعل' && $request->has('product_expiry_date')) {
            $expiryDate = $request->input('product_expiry_date');
        }

        // تحديث المنتج
        $product->update([
            'product_name' => $request->input('product_name'),
            'product_barcode' => $request->input('product_barcode'),
            'product_serial_number' => $request->input('product_serial_number'),
            'product_status' => $request->input('product_status'),
            'store_id' => $request->input('store_id'),  // تأكد من أن القيمة موجودة
            'category_id' => $request->input('category_id'),
            'unit_id' => $request->input('unit_id'),
            'product_sale_price' => $request->input('product_sale_price'),
            'tax_id' => $request->input('tax_id'),
            'tax_rate' => $request->input('tax_rate'),
            'product_total_price' => $request->input('product_total_price'),
            'product_quantity' => $request->input('product_quantity'),
            'product_description' => $request->input('product_description'),
            'product_image' => $imagePath,
            'product_expiry_date' => $expiryDate,
        ]);

        return redirect()->route('products.index')->with('success', 'تم تحديث المنتج بنجاح');
    }

    public function show()
    {
        $products = Product::all(); // أو أي منطق آخر لجلب المنتجات
        return view('products.products', compact('products'));
    }

    public function getProductByBarcode($barcode)
    {
        $product = Product::with(['category', 'unit'])->where('product_barcode', $barcode)->first();

        if ($product) {
            return response()->json([
                'id' => $product->id,
                'product_name' => $product->product_name,
                'category_id' => $product->category_id,
                'category_name' => $product->category->c_name,
                'unit_id' => $product->unit_id,
                'unit_name' => $product->unit->unit_name,
                'barcode' => $product->product_barcode,
                'price' => $product->product_sale_price
            ]);
        } else {
            return response()->json([], 404);
        }
    }
    public function searchProductByBarcode(Request $request)
    {
        $barcode = $request->input('barcode');

        // Find product by barcode
        $product = Product::with(['category', 'unit'])->where('product_barcode', $barcode)->first();

        if ($product) {
            return response()->json([
                'success' => true,
                'product' => [
                    'id' => $product->id,
                    'product_name' => $product->product_name,
                    'category_id' => $product->category_id,
                    'category_name' => $product->category->c_name,
                    'unit_id' => $product->unit_id,
                    'unit_name' => $product->unit->unit_name,
                    'barcode' => $product->product_barcode,
                    'price' => $product->product_sale_price
                ]
            ]);
        } else {
            return response()->json(['success' => false, 'message' => 'Product not found'], 404);
        }
    }

}
