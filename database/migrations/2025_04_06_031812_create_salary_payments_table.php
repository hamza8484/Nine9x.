<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalaryPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('salary_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');  // علاقة مع جدول الموظفين
            $table->foreignId('cashbox_id')->nullable()->constrained()->onDelete('set null');  // علاقة مع الخزنة
            $table->foreignId('account_id')->nullable()->constrained()->onDelete('set null');  // علاقة مع الحساب
            $table->foreignId('branch_id')->nullable()->constrained()->onDelete('set null');  // علاقة مع الفرع
            $table->unsignedBigInteger('user_id'); // المستخدم الذي أنشأ المورد
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->decimal('amount', 10, 2);  // مبلغ الراتب الأساسي
            $table->decimal('gross_salary', 10, 2);  // الراتب الإجمالي قبل الخصومات
            $table->decimal('bonus', 10, 2)->default(0);  // المكافآت
            $table->decimal('deduction', 10, 2)->default(0);  // الخصومات
            $table->decimal('tax_deduction', 10, 2)->default(0);  // خصم الضريبة
            $table->decimal('total_deductions', 10, 2)->default(0);  // مجموع الخصومات
            $table->decimal('net_amount', 10, 2);  // المبلغ الصافي بعد الخصومات

            $table->date('payment_date');  // تاريخ الدفع
            $table->enum('payment_status', ['paid', 'unpaid'])->default('unpaid');  // حالة الدفع
            $table->string('payment_method')->nullable();  // طريقة الدفع (مثل تحويل بنكي، شيك، نقدًا)
            $table->string('payment_reference')->nullable();  // مرجع الدفع (مثل رقم التحويل البنكي أو الشيك)
            $table->string('currency')->default('SAR');  // العملة
            $table->string('payment_received_by')->nullable();  // الشخص الذي استلم الدفع
            $table->text('payment_notes')->nullable();  // ملاحظات إضافية حول الدفع
            $table->string('payment_method_details')->nullable();  // تفاصيل طريقة الدفع

            $table->enum('salary_month', ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December']); // شهر الراتب
            $table->integer('salary_year');  // سنة الراتب
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
        Schema::dropIfExists('salary_payments');
    }
}
