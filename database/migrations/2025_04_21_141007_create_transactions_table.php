<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');      
            $table->unsignedBigInteger('account_id');   
            $table->unsignedBigInteger('cashbox_id');   
            $table->unsignedBigInteger('tax_id')->nullable(); // 🔥 الضريبة (اختياري)
            $table->enum('type', ['credit', 'debit']);  
            $table->decimal('amount', 15, 2);           
            $table->text('description')->nullable();   
            $table->date('transaction_date');           
            $table->timestamps();

            // علاقات الجداول
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('account_id')->references('id')->on('accounts')->onDelete('cascade');
            $table->foreign('cashbox_id')->references('id')->on('cashboxes')->onDelete('cascade');
            $table->foreign('tax_id')->references('id')->on('taxes')->onDelete('set null'); // 🔥
        });
    }

    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}
