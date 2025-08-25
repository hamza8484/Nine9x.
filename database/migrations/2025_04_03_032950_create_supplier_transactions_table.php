<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateSupplierTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('supplier_transactions', function (Blueprint $table) {
            $table->id();
            $table->decimal('amount', 10, 2); // المبلغ المدفوع
            $table->decimal('balance', 10, 2); // الرصيد بعد العملية
            $table->decimal('balance_before_transaction', 10, 2); // الرصيد قبل العملية
            $table->enum('payment_method', ['نقدي', 'بطاقة', 'تحويل بنكي'])->default('نقدي'); // طريقة الدفع
            $table->timestamp('date')->default(DB::raw('CURRENT_TIMESTAMP')); // تاريخ السند
            $table->unsignedBigInteger('user_id'); // المستخدم الذي أنشأ المورد
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreignId('supplier_id')->constrained('suppliers')->onDelete('cascade'); // المورد الذي تم الدفع له
            $table->foreignId('cashbox_id')->constrained('cashboxes')->onDelete('cascade'); // الخزنة المرتبطة بالسند
            $table->foreignId('account_id')->nullable()->constrained('accounts')->onDelete('set null'); // ربط الحساب بالمعاملة
            $table->text('notes')->nullable(); // ملاحظات إضافية
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
        Schema::dropIfExists('supplier_transactions');
    }
}
