<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDeletedAtToCashboxesTable extends Migration
{

    
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
{
    Schema::table('cashboxes', function (Blueprint $table) {
        $table->softDeletes(); // هذا يضيف عمود deleted_at
    });
}

public function down()
{
    Schema::table('cashboxes', function (Blueprint $table) {
        $table->dropSoftDeletes(); // لحذفه إذا رجعت في الميجريشن
    });
}

}
