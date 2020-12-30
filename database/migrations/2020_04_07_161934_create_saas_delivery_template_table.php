<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSaasDeliveryTemplateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('saas_delivery_template', function (Blueprint $table) {
            $table->increments('del_temp_id')->comment('自增id');
            $table->integer('mch_id')->default('0')->comment('商家id');
            $table->string('del_temp_name',100)->comment('名称')->nullable();
            $table->string('del_temp_desc',255)->comment('简介')->nullable();
            $table->string('del_temp_delivery_list',255)->comment('包含配送方式列表')->nullable();
            $table->text('del_temp_area_conf')->comment('区域运费配置')->nullable();
            $table->integer('del_temp_priority')->default('0')->comment('优先级(用于处理同一订单多个商品不同物流模板的情况)')->nullable();
            $table->tinyInteger('del_temp_status')->comment('状态')->nullable();
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
        Schema::dropIfExists('saas_delivery_template');
    }
}
