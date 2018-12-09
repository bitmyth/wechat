<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterPaymentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->string('uuid')->nullable()->comment('wechat out trade no');
            $table->string('wx_transaction_id')->nullable()->comment('wechat transaction id');
            $table->integer('wx_total_fee')->nullable()->comment('wechat transaction total fee');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payments', function ($table) {
            $table->dropColumn('uuid');
            $table->dropColumn('wx_total_fee');
            $table->dropColumn('wx_transaction_id');
        });
    }
}
