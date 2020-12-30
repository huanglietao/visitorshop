<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSaasInnerTemplatesPagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('saas_inner_templates_pages', function (Blueprint $table) {
            $table->increments('inner_page_id')->comment('自增id');
            $table->integer('mch_id')->comment('商户id');
            $table->string('inner_page_name')->comment('子页名称');
            $table->integer("inner_page_tid")->comment('内页模板id');
            $table->integer("specifications_id")->comment('规格id');
            $table->integer("inner_base_temp_id")->comment('封面或内页子相应子页id');
            $table->mediumInteger("inner_page_sort")->default(0)->comment("排序");
            $table->string("inner_page_thumb",200)->nullable()->comment('示意图');
            $table->mediumInteger('inner_page_dpi')->comment('精度')->nullable();
            $table->integer("inner_page_year")->nullable()->comment('起始年份');
            $table->mediumInteger("inner_page_photo_count")->nullable()->comment("图片数量");
            $table->integer('inner_page_real_w')->nullable()->comment('宽');
            $table->integer('inner_page_real_h')->nullable()->comment('高');
            $table->longText('inner_page_stage')->nullable()->comment('舞台数据');
            $table->tinyInteger('inner_page_status')->default('1')->comment('状态');
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
        Schema::dropIfExists('saas_inner_templates_pages');
    }
}
