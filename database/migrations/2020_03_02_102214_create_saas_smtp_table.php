<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSaasSmtpTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('saas_smtp', function (Blueprint $table) {
            $table->increments('smtp_id')->comment("自增id");
            $table->string("smtp_address",150)->comment('地址');
            $table->integer("smtp_port")->comment('端口');
            $table->string("smtp_username",50)->comment('用户名')->nullable();
            $table->string ('smtp_password',50)->comment('密码')->nullable();
            $table->string("sender",150)->comment('发送人')->nullable();
            $table->tinyInteger("connecttype")->default('1')->comment('连接类型;1=ssl,2=tls');
            $table->tinyInteger("scene")->default('1')->comment('使用场景,1:内部服务;2:服务器报警;3:客户邮件');
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
        Schema::dropIfExists('saas_smtp');
    }
}
