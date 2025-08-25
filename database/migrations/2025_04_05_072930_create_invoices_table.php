<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('inv_number', 50); // رقم الفاتورة
            $table->date('inv_date')->useCurrent(); // تاريخ الفاتورة

            // العلاقات الإلزامية
            $table->unsignedBigInteger('store_id'); 
            $table->unsignedBigInteger('branch_id'); 
            $table->unsignedBigInteger('customer_id'); 
            $table->unsignedBigInteger('created_by'); 
            $table->unsignedBigInteger('cashbox_id'); 
            $table->unsignedBigInteger('account_id'); 

            $table->enum('inv_payment', ['نقــدي', 'شــبكة', 'آجــل'])->default('نقــدي'); 

            $table->decimal('sub_total', 10, 2)->default(0.00); 
            $table->decimal('discount_value', 10, 2)->default(0.00); 
            $table->enum('discount_type', ['نســبة', 'سعر ثابت'])->default('نســبة'); 

            $table->decimal('vat_value', 10, 2)->default(0.00); 
            $table->decimal('total_deu', 10, 2)->default(0.00); 
            $table->decimal('total_paid', 10, 2)->default(0.00); 
            $table->decimal('total_unpaid', 10, 2)->default(0.00); 
            $table->decimal('total_deferred', 10, 2)->default(0.00); 

            $table->timestamps();

            // المفاتيح الخارجية مع onDelete('cascade') لكل الحقول
            $table->foreign('store_id')->references('id')->on('stores')->onDelete('cascade');
            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('cashbox_id')->references('id')->on('cashboxes')->onDelete('cascade');
            $table->foreign('account_id')->references('id')->on('accounts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoices');
    }
}

