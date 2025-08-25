<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetailsReturnInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('details_return_invoices', function (Blueprint $table) {
            $table->id();
            
            // إضافة العلاقة مع جدول return_invoices
            $table->unsignedBigInteger('return_invoice_id'); 
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('unit_id');
            
            $table->string('product_barcode');
            $table->decimal('quantity', 10, 2)->default(0);
            $table->decimal('product_price', 10, 2)->default(0.00);
            $table->decimal('product_discount', 10, 2)->default(0);
            $table->decimal('total_price', 10, 2)->default(0.00);
            $table->unsignedBigInteger('user_id'); // المستخدم الذي أنشأ المورد
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            // إضافة العلاقات الأجنبية
            $table->foreign('return_invoice_id')->references('id')->on('return_invoices')->onDelete('cascade'); // ربطه مع جدول return_invoices
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('unit_id')->references('id')->on('units')->onDelete('cascade');
            
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
        Schema::dropIfExists('details_return_invoices');
    }
}
