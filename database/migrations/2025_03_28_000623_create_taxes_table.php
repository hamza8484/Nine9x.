<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaxesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('taxes', function (Blueprint $table) {
            $table->id(); // معرف الضريبة
            $table->string('tax_name'); // اسم الضريبة
            $table->decimal('tax_rate', 5, 2); // نسبة الضريبة على الدخل (مثال: 15.00)
            $table->unsignedBigInteger('user_id'); // المستخدم الذي أضاف الضريبة
            $table->timestamps(); // تاريخ الإنشاء والتحديث

            // الربط مع جدول المستخدمين
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
        Schema::dropIfExists('taxes');
    }
}
