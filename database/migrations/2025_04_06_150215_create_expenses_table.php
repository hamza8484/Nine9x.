<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExpensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();  // id الأساسي
            $table->string('exp_inv_number', 50);  // رقم المصروف
            $table->string('exp_name', 255);  // اسم المصروف
            $table->date('exp_date'); // تاريخ المصروف
            $table->string('exp_inv_name', 255); // اسم مورد الفاتورة
            $table->string('exp_sup_number', 50); // رقم فاتورة المورد
            $table->date('exp_sup_date'); // تاريخ فاتورة المورد
            $table->enum('exp_payment', ['نقــدي', 'شــبكة'])->default('نقــدي');
            $table->unsignedBigInteger('category_id')->nullable();  // تحديد النوع والتأكد من أنه unsignedBigInteger
            $table->foreign('category_id')->references('id')->on('e_categories')->onDelete('set null'); // إضافة المفتاح الخارجي
            $table->decimal('exp_amount', 12, 2);  // المبلغ
            $table->decimal('exp_discount', 8, 2); // الخصم
            $table->unsignedBigInteger('tax_id')->nullable();  // tax_id للإشارة إلى جدول taxes
            $table->foreign('tax_id')->references('id')->on('taxes')->onDelete('set null');
            $table->decimal('tax_amount', 8, 2); // قيمة الضريبة
            $table->decimal('final_amount', 12, 2);  // المجموع النهائي بعد حساب الضريبة والخصم
            $table->text('description')->nullable();  // وصف المصروف
            $table->string('created_by');  // اسم المستخدم
            $table->unsignedBigInteger('user_id'); // المستخدم الذي أنشأ المورد
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            // المفاتيح الخارجية الأخرى
            $table->unsignedBigInteger('branch_id');
            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
            $table->unsignedBigInteger('cashbox_id')->nullable();
            $table->foreign('cashbox_id')->references('id')->on('cashboxes')->onDelete('set null');
            $table->unsignedBigInteger('account_id')->nullable();
            $table->foreign('account_id')->references('id')->on('accounts')->onDelete('set null');

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
        Schema::dropIfExists('expenses');
    }
}
