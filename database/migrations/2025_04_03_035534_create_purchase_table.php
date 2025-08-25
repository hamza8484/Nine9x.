<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase', function (Blueprint $table) {
            $table->id();
            $table->string('pur_number', 255); // رقم الفاتورة مع تحديد الحجم
            $table->date('pur_date')->default(now()); // تاريخ الفاتورة
            $table->unsignedBigInteger('store_id'); // المخزن (أو المستودع)
            $table->unsignedBigInteger('branch_id'); // ربط الفاتورة بالفرع
            $table->foreign('store_id')->references('id')->on('stores')->onDelete('cascade'); // ربط مع جدول المخازن
            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade'); // ربط مع جدول الفروع
            $table->unsignedBigInteger('supplier_id'); // التأكد من أن هذا النوع صحيح
            $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('cascade'); // العلاقة مع جدول suppliers
            $table->enum('inv_payment', ['نقــدي', 'شــبكة', 'آجــل'])->default('نقــدي'); // طريقة الدفع
            $table->unsignedBigInteger('user_id'); // المستخدم المرتبط بالفاتورة
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            
            
            $table->text('notes')->nullable(); // ملاحظات إضافية
            
            $table->decimal('sub_total', 10, 2)->default(0.00); // مجموع الفاتورة
            $table->decimal('discount_value', 10, 2)->default(0.00); // الخصم
            $table->enum('discount_type', ['نســبة', 'سعر ثابت'])->default('نســبة'); // نوع الخصم
            $table->decimal('vat_value', 10, 2)->default(0.00); // الضريبة
            $table->decimal('total_deu', 10, 2)->default(0.00); // الإجمالي النهائي
            $table->decimal('total_paid', 10, 2)->default(0.00); // المبلغ المدفوع
            $table->decimal('total_unpaid', 10, 2)->default(0.00); // المبلغ المتبقي
            $table->decimal('total_deferred', 10, 2)->default(0.00); // المبلغ المستحق (للعمليات الآجلة فقط)

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
        Schema::dropIfExists('purchase');
    }
}
