<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSaasUserScoreRuleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('saas_user_score_rule', function (Blueprint $table) {
            $table->increments('score_rule_id')->comment("自增id");
            $table->integer('mch_id')->comment('商户id');
            $table->string('score_rule_name')->comment("规则名称");
            $table->tinyInteger('score_rule_way')->comment("获得途径;1:登录,2:消费,3:签到");
            $table->decimal('score_rule_money')->default(1)->comment("1积分对应的消费金额");
            $table->integer('score_rule_score')->comment("积分数;通过途径可获得的积分数");
            $table->tinyInteger('score_rule_status')->default(1)->comment("状态;1:启用,0:禁用");
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
        Schema::dropIfExists('saas_user_score_rule');
    }
}
