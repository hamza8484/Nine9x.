<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReconciliationLinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reconciliation_lines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reconciliation_id')->constrained('account_reconciliations')->onDelete('cascade'); // ربط مع تسوية الحساب
            $table->foreignId('journal_entry_line_id')->constrained('journal_entry_lines')->onDelete('cascade'); // ربط مع القيد اليومي
            $table->decimal('amount', 15, 2); // المبلغ الذي تم تسويته
            $table->enum('entry_type', ['debit', 'credit']); // نوع الحركة (مدينة أو دائن)
            $table->boolean('is_matched')->default(false); // إذا كانت الحركة مطابقة (True/False)
            $table->timestamps(); // إضافة تواريخ الإنشاء والتحديث
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reconciliation_lines');
    }
}
