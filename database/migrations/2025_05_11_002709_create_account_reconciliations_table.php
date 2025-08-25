<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountReconciliationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('account_reconciliations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('account_id')->constrained('accounts'); // ربط الحساب الذي يتم تسويته
            $table->foreignId('reconciled_by')->nullable()->constrained('users'); // ربط المستخدم الذي أجرى التسوية
            $table->date('reconciliation_date'); // تاريخ التسوية
            $table->decimal('system_balance', 15, 2); // الرصيد المسجل في النظام
            $table->decimal('actual_balance', 15, 2); // الرصيد الفعلي (من المصدر الخارجي مثل البنك)
            $table->enum('status', ['pending', 'reconciled', 'error'])->default('pending'); // حالة التسوية
            $table->text('notes')->nullable(); // ملاحظات إضافية حول التسوية
            $table->timestamps(); // لإضافة created_at و updated_at
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('account_reconciliations');
    }
}
