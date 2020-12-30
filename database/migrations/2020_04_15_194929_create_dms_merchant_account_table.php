<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDmsMerchantAccountTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dms_merchant_account', function (Blueprint $table) {
            $table->increments('id')->comment('自增id');
            $table->integer('mch_id')->comment('商家id')->nullable();
            $table->integer('agent_info_id')->comment('分销商id')->nullable();
            $table->tinyInteger('is_main')->comment('是否为主账号')->nullable();
            $table->string('dms_adm_username',11)->comment('账号');
            $table->string('dms_adm_nickname',50)->comment('昵称')->nullable();
            $table->string('dms_adm_password',50)->comment('密码');
            $table->string('dms_adm_salt',10)->comment('密码盐')->nullable();
            $table->string('dms_adm_avattar',100)->comment('头像')->nullable();
            $table->string('dms_adm_email',100)->comment('邮箱')->nullable();
            $table->string('dms_adm_mobile',15)->comment('手机号')->nullable();
            $table->integer('dms_adm_logintime')->comment('上次登录时间')->nullable();
            $table->tinyInteger('dms_adm_status')->comment('状态;0:禁用,1:启用');
            $table->integer('dms_adm_group_id')->comment('所属组id')->nullable();
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
        Schema::dropIfExists('dms_merchant_account');
    }
}
