<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('company_name', 255); // تحديد طول مناسب
            $table->string('tax_number', 50); // تحديد طول مناسب للرقم الضريبي
            $table->string('commercial_register', 100); // تحديد طول مناسب للسجل التجاري
            $table->string('email')->unique(); // إضافة unique لضمان فريدة البريد الإلكتروني
            $table->string('phone', 20); // تحديد طول مناسب للهاتف
            $table->string('mobile', 20); // تحديد طول مناسب للهاتف المحمول
            $table->text('address'); // يمكن أن يكون من نوع text إذا كان العنوان طويلًا
            $table->text('notes')->nullable(); // ملاحظات يمكن أن تكون فارغة
            $table->string('logo')->nullable(); // إذا كنت تخزن اسم الصورة أو رابطها
            $table->unsignedBigInteger('user_id'); // إضافة user_id
            $table->timestamps();

            // إضافة العلاقة مع جدول users
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('companies');
    }
}
