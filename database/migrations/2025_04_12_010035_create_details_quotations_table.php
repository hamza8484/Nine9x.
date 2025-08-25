<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetailsQuotationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('details_quotations', function (Blueprint $table) {
            $table->id();
            
            $table->unsignedBigInteger('quotation_id'); // معرف عملية الشراء
            $table->unsignedBigInteger('category_id'); // معرف الفئة
            $table->unsignedBigInteger('product_id'); // معرف المنتج
            $table->unsignedBigInteger('unit_id'); // معرف الوحدة
            
            $table->string('product_barcode'); // يمكن تحديد الحجم إذا لزم الأمر
            $table->decimal('quantity', 10, 2)->default(0); // الكمية
            $table->decimal('product_price', 10, 2)->default(0.00); // سعر المنتج
            $table->decimal('product_discount', 10, 2)->default(0); // الخصم على المنتج
            $table->decimal('total_price', 10, 2)->default(0.00); // السعر الإجمالي
            $table->unsignedBigInteger('user_id'); // المستخدم الذي أنشأ المورد
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            // علاقات مع الجداول الأخرى
            $table->foreign('quotation_id')->references('id')->on('quotations')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('unit_id')->references('id')->on('units')->onDelete('cascade');
            
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
        Schema::dropIfExists('details_quotations');
    }
}
