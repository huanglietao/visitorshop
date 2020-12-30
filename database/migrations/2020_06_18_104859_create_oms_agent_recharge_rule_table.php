<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOmsAgentRechargeRuleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('oms_agent_recharge_rule', function (Blueprint $table) {
            $table->increments('rec_rule_id')->comment('自增id');
            $table->integer('mch_id')->comment('商户id');
            $table->string('rec_rule_name',100)->comment('优惠名称');
            $table->decimal('recharge_fee')->comment('充值金额');
            $table->decimal('present_fee')->comment('奖励金额');
            $table->integer('created_at')->comment('创建时间')->nullable();
            $table->integer('updated_at')->comment('更新时间')->nullable();
            $table->integer('deleted_at')->comment('删除时间')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('oms_agent_recharge_rule');
    }
}
