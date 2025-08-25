<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusToUserSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
{
    Schema::table('user_subscriptions', function (Blueprint $table) {
        $table->string('status')->default('active')->after('end_date');
    });
}

public function down()
{
    Schema::table('user_subscriptions', function (Blueprint $table) {
        $table->dropColumn('status');
    });
}

}
