<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDmsAgentApplyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dms_agent_apply', function (Blueprint $table) {
            $table->increments('agent_apply_id')->comment('自增id');
            $table->integer('mch_id')->comment('商户id');
            $table->integer('cust_lv_id')->default("0")->comment('等级id');
            $table->integer('agent_info_id')->comment('商户信息表id')->nullable();
            $table->string('agent_name',50)->comment('店铺名称');
            $table->tinyInteger('agent_type')->default("1")->comment('店铺类型;1:分销,2:天猫,3:淘宝,4:京东,5:实体店,6:合作商户,7:自有商城');
            $table->string('agent_logo',50)->comment('店铺LOGO');
            $table->string('agent_business',100)->comment('店铺主营业务')->nullable();
            $table->string('agent_desc',255)->comment('店铺描述')->nullable();
            $table->string('agent_linkman',50)->comment('联系人');
            $table->string('mobile',20)->comment('手机号码');
            $table->string('agent_url',255)->comment('店铺网址')->nullable();
            $table->string('telephone',20)->comment('客服电话')->nullable();
            $table->string('wechat',30)->comment('微信号')->nullable();
            $table->string('email',30)->comment('邮箱')->nullable();
            $table->integer('province')->comment('省份id');
            $table->integer('city')->comment('城市id');
            $table->integer('district')->comment('区域id');
            $table->string('address',120)->comment('详细地址');
            $table->tinyInteger('is_create_adm')->default("1")->comment('是否已创建账号;1:未创建,2:已创建')->nullable();
            $table->tinyInteger('review_status')->default("1")->comment('审核状态;1:等待审核,2:审核通过;3:审核不通过');
            $table->string('review_failed_msg',255)->comment('审核不通过原因')->nullable();
            $table->string('agent_apply_desc',255)->comment('备注')->nullable();
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
        Schema::dropIfExists('dms_agent_apply');
    }
}
