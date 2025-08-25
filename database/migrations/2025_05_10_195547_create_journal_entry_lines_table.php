<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJournalEntryLinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('journal_entry_lines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('journal_entry_id')->constrained('journal_entries')->onDelete('cascade'); // ربط القيد بالجداول
            $table->foreignId('account_id')->constrained('accounts')->onDelete('cascade'); // ربط الحساب المتأثر
            $table->enum('entry_type', ['debit', 'credit']); // نوع الحركة (مدين أو دائن)
            $table->decimal('amount', 15, 2); // المبلغ
            $table->text('description')->nullable(); // ملاحظات إضافية
            $table->timestamps(); // تواريخ الإنشاء والتحديث
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('journal_entry_lines');
    }
}
