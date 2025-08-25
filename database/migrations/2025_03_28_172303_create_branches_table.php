<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBranchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('branches', function (Blueprint $table) {
            $table->id();  // معرّف فريد للفرع
            $table->string('bra_name');  // اسم الفرع
            $table->string('bra_address');  // عنوان الفرع
            $table->string('bra_phone');  // رقم الهاتف
            $table->string('bra_telephon');  // رقم الجوال

            $table->unsignedBigInteger('user_id'); // المستخدم الذي أنشأ المورد
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            // الحقول الإضافية المتعلقة بالإدارة أو المعلومات المالية
            $table->string('bra_manager')->nullable();  // اسم مدير الفرع
            $table->string('bra_manager_phone')->nullable();  // رقم جوال مدير الفرع
            $table->string('bra_type')->nullable();  // نوع الفرع (رئيسي أو فرعي)
            
            // الحقول الخاصة بالحالة والإدارة
            $table->boolean('is_active')->default(true);  // حالة الفرع: نشط أو غير نشط
            $table->boolean('is_online')->default(false);  // هل الفرع يعمل عبر الإنترنت؟
        
            // ربط الفرع بحساب محاسبي
            $table->foreignId('account_id')->nullable()->constrained('accounts')->onDelete('set null'); // ربط الفرع بحساب محاسبي
            
            // ربط الفرع بالعملة في حالة وجود عملات مختلفة
            $table->enum('currency_code', ['SAR', 'USD', 'AED', 'JOD'])->default('SAR'); // رمز العملة
            
            // إضافة الرصيد الافتتاحي للفرع
            $table->decimal('opening_balance', 15, 2)->default(0); // الرصيد الافتتاحي للفرع
            
            // ملاحظات إضافية
            $table->text('branch_notes')->nullable();  // ملاحظات إضافية
        
            $table->timestamps();  // تاريخ الإنشاء والتعديل
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('branches');
    }
}
