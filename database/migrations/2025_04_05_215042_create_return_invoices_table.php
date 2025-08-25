<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReturnInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('return_invoices', function (Blueprint $table) {
            $table->id(); // معرف المرتجع
            $table->string('ret_inv_number'); // رقم فاتورة المرتجع
            $table->date('ret_inv_date'); // تاريخ الفاتورة المرتجعة
            $table->unsignedBigInteger('store_id'); // فرع المتجر
            $table->foreignId('customer_id')->constrained('customers'); 
            $table->enum('ret_inv_payment', ['نقــدي', 'شــبكة', 'آجــل'])->default('نقــدي');
            $table->foreignId('created_by')->nullable()->constrained('users'); 
            $table->unsignedBigInteger('user_id'); // المستخدم الذي أنشأ المورد
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->decimal('sub_total', 10, 2)->default(0.00);
            $table->decimal('discount_value', 10, 2)->default(0.00); 
            $table->enum('discount_type', ['نســبة', 'سعر ثابت'])->default('نســبة');
            
            // قيمة الضريبة
            $table->decimal('vat_value', 10, 2)->default(0.00); 
            $table->decimal('total_deu', 10, 2)->default(0.00); 
            $table->decimal('total_paid', 10, 2)->default(0.00); 
            $table->decimal('total_unpaid', 10, 2)->default(0.00); 
            $table->decimal('total_deferred', 10, 2)->default(0.00); 
             
            $table->unsignedBigInteger('invoice_id');
            $table->foreign('invoice_id')->references('id')->on('invoices')->onDelete('cascade');
    
            // ربط مع الخزنة
            $table->foreignId('cashbox_id')->nullable()->constrained('cashboxes')->onDelete('set null'); 
            
            // ربط مع الحسابات
            $table->foreignId('account_id')->nullable()->constrained('accounts')->onDelete('set null'); 
            // وقت الإنشاء والتحديث
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
        Schema::dropIfExists('return_invoices');
    }
}
