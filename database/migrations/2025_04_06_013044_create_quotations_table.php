<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuotationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quotations', function (Blueprint $table) {
            $table->id();
            $table->string('qut_number'); // رقم العرض
            $table->date('qut_date')->default(now()); // تاريخ العرض
            $table->enum('qut_status', ['ساري','منتهي', 'إنتظار', 'ملغي'])->default('ساري'); // حالة العرض
            $table->unsignedBigInteger('store_id'); // فرع المتجر
            $table->foreignId('customer_id')->constrained('customers'); // العميل
            
            $table->foreignId('created_by')->nullable()->constrained('users'); // اسم المستخدم (إضافة العمود مرة واحدة فقط)
            $table->unsignedBigInteger('user_id'); // المستخدم الذي أنشأ المورد
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->decimal('sub_total', 10, 2)->default(0.00); // مجموع الفاتورة
            $table->decimal('discount_value', 10, 2)->default(0.00); // الخصم
            $table->enum('discount_type', ['نســبة', 'سعر ثابت'])->default('نســبة'); // نوع الخصم
            
            $table->decimal('vat_value', 10, 2)->default(0.00); // الضريبة
            $table->decimal('total_deu', 10, 2)->default(0.00); // الإجمالي النهائي
            $table->string('qut_notes')->nullable(); // ملاحظات العرض
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
        Schema::dropIfExists('quotations');
    }
}
