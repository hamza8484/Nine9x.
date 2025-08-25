<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_details', function (Blueprint $table) {
            $table->id();
    
            $table->unsignedBigInteger('purchase_id'); // معرف عملية الشراء
            $table->unsignedBigInteger('category_id'); // معرف الفئة
            $table->unsignedBigInteger('product_id'); // معرف المنتج
            $table->unsignedBigInteger('unit_id'); // معرف الوحدة
    
            $table->string('product_barcode'); // يمكن تحديد الحجم إذا لزم الأمر
            $table->decimal('quantity', 10, 2)->default(0); // الكمية
            $table->decimal('product_price', 10, 2)->default(0.00); // سعر المنتج
            $table->decimal('product_discount', 10, 2)->default(0); // الخصم على المنتج
            $table->decimal('total_price', 10, 2)->default(0.00); // السعر الإجمالي
            
            // إضافة عمود user_id
            $table->unsignedBigInteger('user_id'); // معرف المستخدم
    
            // علاقات مع الجداول الأخرى
            $table->foreign('purchase_id')->references('id')->on('purchase')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('unit_id')->references('id')->on('units')->onDelete('cascade');
    
            // إضافة علاقة مع جدول المستخدمين
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
    
            // إضافة الطوابع الزمنية
            $table->timestamps();
        });
    }
    


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purchase_details');
    }
}
