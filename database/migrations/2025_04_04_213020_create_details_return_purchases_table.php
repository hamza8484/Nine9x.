<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetailsReturnPurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('details_return_purchases', function (Blueprint $table) {
            $table->id();
            
            // إضافة العلاقة مع جدول return_invoices
            $table->unsignedBigInteger('ret_purchase_id'); // المفتاح الأجنبي للفاتورة المرتجعة
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('unit_id');
            
            $table->string('product_barcode');
            $table->decimal('quantity', 10, 2)->default(0);
            $table->decimal('product_price', 10, 2)->default(0.00);
            $table->decimal('product_discount', 10, 2)->default(0);
            $table->decimal('total_price', 10, 2)->default(0.00);
            
            // إضافة حقل user_id
            $table->unsignedBigInteger('user_id'); // المفتاح الأجنبي للمستخدم
            
            // إضافة العلاقات الأجنبية
            $table->foreign('ret_purchase_id')->references('id')->on('ret_purchases')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('unit_id')->references('id')->on('units')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade'); // العلاقة مع جدول users
            
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
        Schema::dropIfExists('details_return_purchases');
    }
}

