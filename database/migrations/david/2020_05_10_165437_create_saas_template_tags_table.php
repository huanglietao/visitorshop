<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSaasTemplateTagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('saas_template_tags', function (Blueprint $table) {
            $table->increments('temp_tags_id')->comment('自增id');
            $table->integer('mch_id')->default('0')->comment('商户id');
            $table->string('temp_tages_name','30')->comment('名称');
            $table->string("temp_tags_sign",'20')->nullable()->comment('标识');
            $table->string("temp_tags_desc",150)->nullable()->comment('描述');
            $table->string("temp_tags_thumb",200)->nullable()->comment('示意图');
            $table->tinyInteger('temp_tags_status')->default('1')->comment('状态');
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
        Schema::dropIfExists('saas_template_tags');
    }
}
