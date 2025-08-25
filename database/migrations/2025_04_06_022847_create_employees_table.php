<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('emp_name')->unique();
            $table->string('emp_number', 20)->unique();
            $table->string('emp_id_number')->unique(); // رقم الهوية
            $table->integer('emp_age')->nullable(); // العمر
            $table->decimal('emp_salary', 10, 2);
            $table->decimal('emp_allowance', 10, 2)->nullable();
            $table->date('emp_birth_date')->nullable(); // تاريخ الميلاد
            $table->date('emp_hire_date'); // تاريخ البدء في التوظيف
            $table->string('emp_phone')->nullable(); // رقم الهاتف
            $table->string('emp_mobile')->nullable(); // رقم الجوال
            $table->string('emp_email')->unique(); // الايميل
            $table->enum('emp_department', [
                'HR', 
                'Finance', 
                'IT', 
                'Marketing', 
                'Sales', 
                'Operations', 
                'Customer Service', 
                'R&D', 
                'Logistics', 
                'Legal', 
                'Procurement', 
                'Administration'
            ])->default('HR');
            $table->string('emp_image')->nullable(); // صورة الموظف
            $table->string('emp_position'); // الوظيفة
            $table->string('emp_id_image')->nullable(); // صورة الهوية
            $table->string('emp_contract_image')->nullable(); // صورة العقد
            $table->enum('emp_status', ['active', 'inactive'])->default('active');
            $table->string('emp_notes')->nullable();
            $table->unsignedBigInteger('user_id'); // المستخدم الذي أنشأ المورد
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('employees');
    }
}
