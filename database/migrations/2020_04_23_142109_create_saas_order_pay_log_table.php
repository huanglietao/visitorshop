<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSaasOrderPayLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('saas_order_pay_log', function (Blueprint $table) {
            $table->increments('pay_log_id');
            $table->integer('mch_id')->default(0)->comment('商户id');
            $table->integer('user_id')->default(0)->comment('用户id');
            $table->tinyInteger('user_type')->default(0)->comment('用户类型');
            $table->decimal('amount')->comment('金额');
            $table->string('outer_trade_no',50)->comment('交易流水号');
            $table->tinyInteger('pay_type')->default(0)->comment('支付方式');
            $table->tinyInteger('pay_status')->default(0)->comment('支付状态');
            $table->integer('created_at')->comment("创建时间")->nullable();
            $table->integer('updated_at')->comment("更新时间")->nullable();
            $table->integer('deleted_at')->comment("删除时间")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('saas_order_pay_log');
    }
}
