<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDmsAuthGroupTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dms_auth_group', function (Blueprint $table) {
            $table->increments('dms_group_id')->comment('自增id');
            $table->integer("dms_group_pid")->default(0)->comment('父级id');
            $table->integer("agent_id")->default(0)->comment('分销商id');
            $table->string('dms_group_name',30)->default('')->comment("角色名称");
            $table->text("dms_group_rule")->nullable()->comment('规则id');
            $table->tinyInteger('dms_group_status')->default(1)->comment('状态');
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
        Schema::dropIfExists('dms_auth_group');
    }
}
