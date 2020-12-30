<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSaasOrderPushQueueTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('saas_order_push_queue', function (Blueprint $table) {
            $table->increments('order_push_id')->comment('自增id');
            $table->integer('mch_id')->default('0')->comment('商家id');
            $table->integer('order_id')->comment('订单id');
            $table->enum('order_push_status',['ready','progress','finish','error'])->default('ready')->comment('订单推送状态;ready:准备,progress:进行中,finish:结束,error:异常');
            $table->string('err_msg',255)->comment('异常时的错误信息')->nullable();
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
        Schema::dropIfExists('saas_order_push_queue');
    }
}
