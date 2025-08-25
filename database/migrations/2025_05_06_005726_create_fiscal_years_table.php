<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFiscalYearsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fiscal_years', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // اسم السنة المالية (مثلاً: "2024/2025")
            $table->date('start_date'); // تاريخ البداية
            $table->date('end_date'); // تاريخ النهاية
            $table->enum('status', ['نشطة', 'غير نشطة', 'مؤرشفة'])->default('نشطة'); // حالة السنة المالية
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('fiscal_years');
    }

}
