<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stores', function (Blueprint $table) {
            $table->id(); // معرف المخزن
            $table->string('store_name')->unique(); // اسم المخزن (فريد)
            $table->text('store_location')->nullable(); // الموقع داخل المخزن
            $table->text('store_notes')->nullable(); // ملاحظات إضافية عن المخزن
            $table->integer('total_stock')->default(0); // إجمالي المخزون
            $table->decimal('inventory_value', 10, 2)->default(0); // قيمة المخزون
            $table->enum('status', ['active', 'inactive', 'under_maintenance'])->default('active'); // حالة المخزن
            $table->string('created_by', 255)->default('System');  // إضافة قيمة افتراضية
            $table->foreignId('branch_id')->constrained()->onDelete('cascade');  // ربط المخزن بالفرع
            $table->unsignedBigInteger('user_id'); // المستخدم الذي أنشأ المورد
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps(); // تاريخ الإنشاء والتحديث
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stores');
    }
}
