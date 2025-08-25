<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateClientTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('client_transactions', function (Blueprint $table) {
            $table->id();
            $table->decimal('amount', 10, 2); // المبلغ المستلم
            $table->decimal('balance', 10, 2); // الرصيد بعد العملية
            $table->enum('payment_method', ['نقدي', 'بطاقة']); // طريقة الدفع
            $table->timestamp('date')->default(DB::raw('CURRENT_TIMESTAMP')); // تاريخ السند
            $table->unsignedBigInteger('user_id'); 
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreignId('client_id')->constrained('customers')->onDelete('cascade'); // العميل الذي تم القبض منه
            $table->foreignId('cashbox_id')->constrained('cashboxes')->onDelete('cascade'); // الخزنة المرتبطة بالسند
            $table->foreignId('account_id')->nullable()->constrained('accounts')->onDelete('set null'); // ربط الحساب بالمعاملة
            $table->text('notes')->nullable(); // ملاحظات إضافية
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client_transactions');
    }
};
