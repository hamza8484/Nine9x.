<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('product_name', 255); // اسم الصنف
            $table->string('product_barcode', 20)->unique(); // باركود الصنف (فريد)
            $table->string('product_serial_number', 50)->unique(); // سيريال نمبر (فريد)
            $table->unsignedBigInteger('store_id'); // المخزن
            $table->unsignedBigInteger('category_id'); // المجموعة
            $table->unsignedBigInteger('unit_id'); // الوحدة
            $table->unsignedBigInteger('tax_id'); // معرف الضريبة
            $table->decimal('tax_rate', 5, 2)->nullable(); // حقل نسبة الضريبة
            $table->date('product_expiry_date')->nullable(); // تاريخ الصلاحية
            $table->string('product_image'); // صورة الصنف
            $table->integer('product_stock_quantity')->default(0); // الكمية في المخزن
            $table->integer('product_quantity')->default(0); // الكمية الجديدة
            $table->decimal('product_sale_price', 10, 2)->default(0); // سعر الصنف
            $table->decimal('product_total_price', 10, 2)->default(0); // مجموع سعر الصنف بعد الضريبة
            $table->enum('product_status', ['مفعل', 'غير مفعل', 'under_maintenance'])->default('مفعل'); // حالة الصنف
            $table->enum('expiry_date_status', ['مفعل', 'غير مفعل'])->default('غير مفعل'); // حالة تاريخ الصلاحية
            $table->unsignedBigInteger('user_id'); // تغيير created_by إلى user_id
            $table->text('product_description')->nullable(); // الوصف
            $table->timestamps();

            // روابط مع الجداول الأخرى
            $table->foreign('store_id')->references('id')->on('stores')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->foreign('unit_id')->references('id')->on('units')->onDelete('cascade');
            $table->foreign('tax_id')->references('id')->on('taxes')->onDelete('cascade'); // الربط مع جدول الضرائب
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade'); // ربط مع جدول المستخدمين

            // فهارس إضافية
            $table->index('product_barcode');
            $table->index('product_serial_number');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
