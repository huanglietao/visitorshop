<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCmsAdminTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cms_admin', function (Blueprint $table) {
            $table->increments('cms_adm_id');
            $table->string("cms_adm_username",20)->default('')->comment('用户名');
            $table->string('cms_adm_nickname',50)->default('')->nullable()->comment("昵称");
            $table->string("cms_adm_password",32)->default('')->comment('密码');
            $table->string("cms_adm_salt",30)->default('')->nullable()->comment('密码盐');
            $table->string("cms_adm_avatar",100)->default('')->nullable()->comment('头像');
            $table->string("cms_adm_email",100)->default('')->nullable()->comment('邮箱');
            $table->string("cms_adm_mobile",15)->default('')->nullable()->comment('邮箱');
            $table->tinyInteger('cms_adm_status')->default(1)->comment('状态');
            $table->integer('cms_adm_logintime')->default(0)->nullable()->comment("登录时间");
            $table->integer('cms_adm_group_id')->default(1)->comment("所属组id");
            $table->tinyInteger('deleted_at')->default(0)->nullable()->comment('软删除');
            $table->integer('created_at')->comment("创建时间");
            $table->integer("updated_at")->comment("更新时间");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cms_admin');
    }
}
