<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->id(); // معرّف الحساب
            $table->string('name'); // اسم الحساب
            $table->string('code')->unique(); // رمز الحساب
            $table->foreignId('account_type_id')->constrained('account_types'); // ربط الحساب بنوع الحساب
            $table->foreignId('parent_id')->nullable()->constrained('accounts')->onDelete('cascade'); // ربط الحساب بحساب رئيسي
            $table->decimal('balance', 15, 2)->default(0); // رصيد الحساب
            $table->boolean('is_active')->default(true);
            
            $table->enum('account_nature', ['debit', 'credit']); // طبيعة الحساب (مدينة/دائنة)
            $table->enum('account_group', ['asset', 'liability', 'equity', 'revenue', 'expense']); // مجموعة الحساب
          
          
            $table->enum('category', ['financial', 'sales', 'expenses', 'income', 'liabilities'])->nullable(); // التصنيف المالي
            $table->date('opening_date')->nullable(); // تاريخ افتتاح الحساب
            $table->decimal('opening_balance', 15, 2)->default(0); // الرصيد الافتتاحي
            $table->timestamp('last_modified_date')->nullable(); // تاريخ آخر تعديل
            $table->enum('sub_account_type', ['current', 'capital', 'revenue', 'expense'])->nullable(); // نوع الحساب الفرعي
            $table->enum('currency_code', ['SAR', 'USD', 'AED', 'JOD'])->default('SAR'); // رمز العملة
            $table->enum('advanced_status', ['active', 'inactive', 'closed', 'suspended'])->default('active'); // حالة الحساب المتقدمة
            $table->text('account_description')->nullable();
            // إضافة الحقول التي تتبع من قام بإنشاء وتحديث الحساب
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null'); // معرف المستخدم الذي قام بإنشاء الحساب
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null'); // معرف المستخدم الذي قام بتحديث الحساب
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
        Schema::dropIfExists('accounts');
    }
}
