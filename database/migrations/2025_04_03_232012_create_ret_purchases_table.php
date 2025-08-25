<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRetPurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ret_purchases', function (Blueprint $table) {
            $table->id();
            $table->string('ret_pur_number')->unique(); // إضافة unique هنا لضمان أن الرقم فريد
            $table->date('ret_pur_date')->default(now());
            $table->unsignedBigInteger('store_id');
            $table->unsignedBigInteger('branch_id');
            $table->foreignId('supplier_id')->constrained('suppliers');
            $table->enum('ret_pur_payment', ['نقــدي', 'شــبكة', 'آجــل'])->default('نقــدي');
           
            
            // إضافة حقل user_id
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');  // ربط مع جدول users

            $table->unsignedBigInteger('purchase_id'); // ربط الفاتورة الأصلية مع المرتجع
            $table->string('return_reason')->nullable(); // سبب المرتجع
            $table->text('notes')->nullable(); // ملاحظات إضافية
            $table->decimal('sub_total', 10, 2)->default(0.00);
            $table->decimal('discount_value', 10, 2)->default(0.00);
            $table->enum('discount_type', ['نســبة', 'سعر ثابت'])->default('نســبة');
            $table->decimal('vat_value', 10, 2)->default(0.00); // الضريبة
            $table->decimal('total_deu', 10, 2)->default(0.00); // الإجمالي النهائي
            $table->decimal('total_paid', 10, 2)->default(0.00); // المبلغ المدفوع
            $table->decimal('total_unpaid', 10, 2)->default(0.00); // المبلغ المتبقي
    
            $table->foreign('purchase_id')->references('id')->on('purchase')->onDelete('cascade');
            $table->foreign('store_id')->references('id')->on('stores')->onDelete('cascade');
            // ربط مع الخزنة
            $table->foreignId('cashbox_id')->nullable()->constrained('cashboxes')->onDelete('set null'); // الخزنة التي تم الدفع منها (إذا كان الدفع نقديًا)
            
            // ربط مع الحسابات
            $table->foreignId('account_id')->nullable()->constrained('accounts')->onDelete('set null'); // الحساب الذي تم الدفع منه (إذا كان الدفع عن طريق حساب بنكي)
            
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
        Schema::dropIfExists('ret_purchases');
    }
}

