<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSaasUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('saas_user', function (Blueprint $table) {
            $table->increments('user_id')->comment("自增id");
            $table->integer('mch_id')->comment("商户id");
            $table->integer('cust_lv_id')->default(0)->comment("等级id");
            $table->string('user_name',100)->comment('用户名');
            $table->string('user_nickname',100)->comment('昵称');
            $table->string('password',100)->comment('密码');
            $table->string('salt',20)->comment('盐值');
            $table->string('user_mobile',25)->comment('手机号');
            $table->string('user_email',50)->comment('邮箱');
            $table->string('user_avatar',255)->comment('头像');
            $table->integer('user_birthday')->comment('生日');
            $table->decimal('balance',10)->comment('余额');
            $table->integer('score')->comment('积分');
            $table->tinyInteger('status')->comment('状态;1:启用,0:禁用');
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
        Schema::dropIfExists('saas_user');
    }
}
