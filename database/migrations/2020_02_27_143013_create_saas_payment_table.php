<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSaasPaymentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('saas_payment', function (Blueprint $table) {
            $table->increments('pay_id')->comment('自增id');
            $table->integer('partner_id')->comment('上级id')->nullable();
            $table->integer('mch_id')->default('0')->comment('商家id');
            $table->string("pay_name",30)->comment('支付名称');
            $table->tinyInteger("pay_type")->comment('支付流程;1:线上,2:线下')->nullable();
            $table->string("pay_class_name",30)->comment('关联流程标识;alipay,wxpay,...')->nullable();
            $table->string ('pay_desc',255)->comment('描述')->nullable();
            $table->string("pay_logo",255)->comment('图标')->nullable();
            $table->string("pay_note",255)->comment('支付说明(需要给客户指引的信息)')->nullable();
            $table->tinyInteger("pay_poundage_type")->comment('手续费方式 1百分比 2固定值')->nullable();
            $table->decimal("pay_poundage",10)->comment('手续费')->nullable();
            $table->text("pay_config_param")->comment('配置参数,json数据对象')->nullable();
            $table->tinyInteger("pay_client_type")->comment('适用终端;1:PC端 2:移动端 3:通用')->nullable();
            $table->Integer("sort")->comment('排序')->nullable();
            $table->tinyInteger("pay_status")->comment('状态;0:禁用,1:启用')->nullable();
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
        Schema::dropIfExists('saas_payment');
    }
}
