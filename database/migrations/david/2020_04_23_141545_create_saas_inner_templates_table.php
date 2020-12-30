<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSaasInnerTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('saas_inner_templates', function (Blueprint $table) {
            $table->increments('inner_temp_id')->comment('自增id');
            $table->integer('mch_id')->comment('商户id');
            $table->string('inner_temp_name')->comment('模板名称');
            $table->integer("goods_type_id")->comment('产品类型id');
            $table->integer("inner_temp_theme_id")->comment('模板主题分类id');
            $table->integer("specifications_id")->comment('规格id');
            $table->string("inner_temp_no",100)->nullable()->comment('模板编号');
            $table->string("inner_temp_desc",150)->nullable()->comment('描述');
            $table->mediumInteger("inner_temp_photo_count")->nullable()->comment("图片总数");
            $table->mediumInteger("inner_temp_sort")->default(0)->comment("排序");
            $table->string("inner_temp_thumb",200)->nullable()->comment('示意图');
            $table->tinyInteger("inner_temp_check_status")->default(1)->comment('审核状态');
            $table->integer("inner_temp_start_year")->nullable()->comment('起始年份');
            $table->tinyInteger('inner_spec_style')->default('1')->comment('规格标签');
            $table->tinyInteger('inner_temp_status')->default('1')->comment('状态');
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
        Schema::dropIfExists('saas_inner_templates');
    }
}
