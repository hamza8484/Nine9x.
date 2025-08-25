<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSuppliersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('sup_name')->index();  // اسم المورد
            $table->string('sup_tax_number')->unique();  // الرقم الضريبي
            $table->string('sup_phone', 20)->index();  // الهاتف
            $table->string('sup_mobile', 20)->index();  // الجوال
            $table->string('sup_commercial_record')->unique();  // السجل التجاري
            $table->decimal('sup_balance', 15, 2)->default(0);  // الرصيد
            $table->text('sup_address');  // العنوان
            $table->text('sup_notes')->nullable();  // الملاحظات
    
            // ربط المستخدم
            $table->unsignedBigInteger('user_id'); // المستخدم الذي أنشأ المورد
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
    
            // إضافة حقل لتتبع الحسابات
            $table->foreignId('account_id')->nullable()->constrained('accounts')->onDelete('set null'); // ربط الحساب المحاسبي
    
            $table->timestamps();  // وقت الإنشاء والتعديل
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('suppliers');
    }
}

