<?php

// في موجه الأوامر: php artisan make:controller BarcodeController

namespace App\Http\Controllers;

use App\Product;
use App\Company;
use Illuminate\Http\Request;

class BarcodeController extends Controller
{
    // لعرض صفحة المنتجات مع ملصقات الباركود
    public function showProductList()
    {
        $company = Company::first();
        $products = Product::all(); // استرجاع جميع المنتجات
        return view('products.print_Product_barcode', compact('company','products'));
    }

    // لعرض صفحة طباعة ملصقات الباركود
    public function printBarcodeLabels()
    {
        $products = Product::all(); // استرجاع جميع المنتجات
        return view('products.print_barcode', compact('products'));
    }
}



