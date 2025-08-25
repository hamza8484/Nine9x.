<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('cus_name');  // اسم العميل
            $table->string('cus_tax_number');  // الرقم الضريبي
            $table->string('cus_phone');  // الهاتف
            $table->string('cus_mobile');  // الجوال
            $table->string('cus_commercial_record');  // السجل التجاري
            $table->decimal('cus_balance', 15, 2)->default(0);  // الرصيد
            $table->string('cus_maile');
            $table->text('cus_address');  // العنوان
            $table->text('cus_notes')->nullable();  // الملاحظات
            $table->unsignedBigInteger('user_id'); // المستخدم الذي أنشأ المورد
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('customers');
    }
}
