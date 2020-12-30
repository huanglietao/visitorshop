<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSaasSuppliersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('saas_suppliers', function (Blueprint $table) {
            $table->increments('sup_id')->comment("自增id");
            $table->integer('mch_id')->default("0")->comment("商家id");
            $table->string('sup_name',50)->comment("供应商名称")->nullable();
            $table->string('sup_code',30)->comment("供应商编号")->nullable();
            $table->string('sup_contacts',30)->comment("联系人")->nullable();
            $table->string('sup_telephone',30)->comment("电话/手机")->nullable();
            $table->string('sup_region',30)->comment("所在区域;中国分为:华南,华西,华北,华东,华中")->nullable();
            $table->integer('sup_province')->comment("所在省")->nullable();
            $table->integer('sup_city')->comment("所在市")->nullable();
            $table->integer('sup_area')->comment("所在区")->nullable();
            $table->integer('sup_type')->comment("供应商类型;1:主力,2:备选(同一市区有且只有一个主力)")->nullable();
            $table->string('sup_service_area')->comment("服务区域;供应商所能生产的订单收货人区域")->nullable();
            $table->integer('sup_capacity')->comment("供应商产能")->nullable();
            $table->integer('sup_allocation_quantity')->comment("订单分配量;推送订单数量的上限")->nullable();
            $table->string('sup_capacity_unit',10)->comment("产能单位")->nullable();
            $table->tinyInteger('sup_status')->default('1')->comment("状态;1:启用,0:禁用")->nullable();
            $table->integer('sort')->default("0")->comment("排序");
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
        Schema::dropIfExists('saas_suppliers');
    }
}
