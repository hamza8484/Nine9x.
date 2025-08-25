<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJournalEntriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('journal_entries', function (Blueprint $table) {
            $table->id();
            $table->date('transaction_date'); // تاريخ المعاملة
            $table->string('description')->nullable(); // وصف المعاملة
            $table->foreignId('fiscal_year_id')->constrained('fiscal_years')->onDelete('cascade'); // ربط السنة المالية بالحركة
            $table->string('reference_number')->nullable(); // رقم مرجعي للمعاملة (اختياري)
            
            // إضافة الحقول الخاصة بالحساب المدين والدائن
            $table->foreignId('debit_account_id')->nullable()->constrained('accounts')->onDelete('set null'); // ربط الحساب المدين
            $table->foreignId('credit_account_id')->nullable()->constrained('accounts')->onDelete('set null'); // ربط الحساب الدائن
            
            $table->timestamps(); // تواريخ الإنشاء والتحديث
        });
    }



    public function down()
    {
        Schema::dropIfExists('journal_entries');
    }

}
