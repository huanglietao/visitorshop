<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCmsAuthGroupTable extends Migration
{
    /**
     * Run the migrations.
     *  角色组
     * @return void
     */
    public function up()
    {
        Schema::create('cms_auth_group', function (Blueprint $table) {
            $table->increments('cms_group_id')->comment('自增id');
            $table->integer("cms_group_pid")->default(0)->comment('父级id');
            $table->string('cms_group_name',30)->default('')->comment("角色名称");
            $table->text("cms_group_rule")->nullable()->comment('规则id');
            $table->tinyInteger('cms_group_status')->default(1)->comment('状态');
            $table->integer('deleted_at')->nullable()->comment('软删除');
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
        Schema::dropIfExists('cms_auth_group');
    }
}
