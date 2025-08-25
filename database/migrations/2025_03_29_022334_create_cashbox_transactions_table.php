<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCashboxTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cashbox_transactions', function (Blueprint $table) {
            $table->id();

            // الربط مع الخزنة
            $table->foreignId('cashbox_id')->constrained('cashboxes')->onDelete('cascade'); // ربط مع جدول cashboxes

            $table->unsignedBigInteger('user_id'); // المستخدم الذي أنشأ المورد
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            // نوع الحركة (إيداع أو سحب)
            $table->enum('type', ['deposit', 'withdrawal']);

            // المبلغ
            $table->decimal('cash_amount', 15, 2);

            // وصف الحركة
            $table->string('cash_description')->nullable();

            // الطوابع الزمنية
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
        Schema::dropIfExists('cashbox_transactions');
    }
}
