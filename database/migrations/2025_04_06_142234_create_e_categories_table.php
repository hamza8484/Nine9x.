<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateECategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('e_categories', function (Blueprint $table) {
            $table->id();
            $table->string('cat_name', 255);  // اسم الفئة
            $table->text('description')->nullable();  // وصف الفئة
            $table->string('created_by', 255);  // اسم المستخدم الذي أضاف الفئة
            $table->unsignedBigInteger('user_id'); // المستخدم الذي أنشأ المورد
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();  // إنشاء الحقول created_at و updated_at
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('e_categories');
    }
}
