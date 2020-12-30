<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSaasDeliveryQueueTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('saas_delivery_queue', function (Blueprint $table) {
            $table->increments('delivery_push_id')->comment('自增id');
            $table->integer('mch_id')->default('0')->comment('商家id');
            $table->integer('agent_code')->comment('分销商编号')->nullable();
            $table->integer('order_id')->comment('订单id');
            $table->enum('delivery_push_status',['ready','progress','finish','error'])->default('ready')->comment('物流回写信息推送状态;ready:准备,progress:进行中,finish:结束,error:异常');
            $table->string('delivery_name',50)->comment('快递简称')->nullable();
            $table->string('delivery_code',255)->comment('运单号')->nullable();
            $table->tinyInteger('times')->default('0')->comment('执行次数')->nullable();
            $table->text('error_msg')->comment('错误信息')->nullable();
            $table->integer('start_time')->comment('队列开始时间')->nullable();
            $table->integer('end_time')->comment('队列结束时间')->nullable();
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
        Schema::dropIfExists('saas_delivery_queue');
    }
}
