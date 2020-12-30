<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSaasCustomerLevelTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('saas_customer_level', function (Blueprint $table) {
            $table->increments('cust_lv_id')->comment('自增id');
            $table->integer('mch_id')->comment('商户id');
            $table->tinyInteger('cust_lv_type')->comment('客户等级类型;1:分销商,2:一般会员');
            $table->string('cust_lv_name',50)->comment('等级名称');
            $table->string('cust_lv_desc',255)->comment('等级描述');
            $table->integer('cust_lv_discount')->comment('等级折扣;50代表打5折');
            $table->integer('cust_lv_score')->comment('等级积分,当会员等级达到此值时，会员等级提升')->nullable();
            $table->integer('sort')->comment('排序');
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
        Schema::dropIfExists('saas_customer_level');
    }
}
