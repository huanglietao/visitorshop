<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSaasDeliveryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('saas_delivery', function (Blueprint $table) {
            $table->increments('delivery_id')->comment('自增id');
            $table->integer('mch_id')->default('0')->comment('商家id');
            $table->string('delivery_name',50)->comment('配送名称;如:普通快递')->nullable();
            $table->string('delivery_show_name',50)->comment('终端展示名称;如:默认圆通')->nullable();
            $table->string('delivery_express_list',100)->comment('包含快递方式列表')->nullable();
            $table->string('delivery_desc',255)->comment('使用场景')->nullable();
            $table->tinyInteger('delivery_is_cash')->comment('是否货到付款;0:否,1:是')->nullable();
            $table->tinyInteger('delivery_status')->comment('状态;0:禁用,1:启用')->nullable();
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
        Schema::dropIfExists('saas_delivery');
    }
}
