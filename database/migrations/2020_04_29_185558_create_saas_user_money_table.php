<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSaasUserMoneyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('saas_user_money', function (Blueprint $table) {
            $table->increments('user_money_id')->comment("自增id");
            $table->integer("user_id")->comment("会员id,与saas_user表关联");
            $table->integer("mch_id")->comment("商家id");
            $table->string("recharge_no",50)->comment("交易流水号");
            $table->string("trade_no",50)->comment("第三方交易流水号");
            $table->tinyInteger("money_type")->comment("交易类型;1:消费,2:充值");
            $table->decimal("amount",15)->comment("交易金额");
            $table->decimal("balance",15)->comment("账户余额");
            $table->string("operator",100)->comment("操作人");
            $table->string("note",255)->comment("备注")->nullable();
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
        Schema::dropIfExists('saas_user_money');
    }
}
