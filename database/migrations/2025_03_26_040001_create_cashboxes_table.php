<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCashboxesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cashboxes', function (Blueprint $table) {
            $table->id(); // معرّف الخزنة
            $table->string('cash_name'); // اسم الخزنة
            $table->decimal('cash_balance', 15, 2)->default(0); // الرصيد الحالي للخزنة
            $table->decimal('opening_balance', 15, 2)->default(0); // الرصيد الافتتاحي
            $table->date('start_date')->nullable(); // تاريخ بداية الخزنة
            $table->decimal('last_opening_balance', 15, 2)->nullable(); // الرصيد الافتتاحي الأخير
            $table->decimal('usable_balance', 15, 2)->default(0); // الرصيد المتاح للاستخدام
            $table->decimal('previous_balance', 15, 2)->nullable();
            $table->decimal('balance_at_last_reconciliation', 15, 2)->nullable(); // رصيد الخزنة في آخر تسوية
            $table->unsignedBigInteger('user_id'); // المستخدم الذي أنشأ المورد
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->enum('cashbox_type', ['main', 'sub', 'temporary', 'revenue', 'expense'])->default('main'); // نوع الخزنة
            $table->enum('reconciliation_status', ['pending', 'completed', 'reconciled'])->default('pending'); // حالة التسوية
            $table->enum('currency_code', ['SAR', 'USD', 'AED', 'JOD'])->default('SAR'); // رمز العملة
            $table->decimal('cash_limit', 15, 2)->nullable(); // الحد الأقصى للخزنة
            $table->date('limit_effective_date')->nullable(); // تاريخ سريان الحد الأقصى
            $table->enum('status', ['active', 'inactive', 'closed'])->default('active'); // حالة الخزنة
            $table->text('notes')->nullable(); // ملاحظات إضافية
            $table->text('description')->nullable(); // وصف الخزنة
         
            // إضافة الحقول التي تتبع من قام بإنشاء وتحديث الخزنة
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null'); // معرف المستخدم الذي قام بإنشاء الخزنة
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null'); // معرف المستخدم الذي قام بتحديث الخزنة
            
            $table->timestamps(); // timestamps (created_at, updated_at)
        });
    }
    



    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cashboxes'); // حذف الجدول عند التراجع عن الميجريشن
    }
}
