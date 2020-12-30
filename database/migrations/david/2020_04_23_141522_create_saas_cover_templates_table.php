<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSaasCoverTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('saas_cover_templates', function (Blueprint $table) {
            $table->increments('cover_temp_id')->comment('自增id');
            $table->integer('mch_id')->comment('商户id');
            $table->string('cover_temp_name')->comment('模板名称');
            $table->integer("goods_type_id")->comment('产品类型id');
            $table->integer("cover_temp_theme_id")->comment('模板主题分类id');
            $table->integer("specifications_id")->comment('规格id');
            $table->string("cover_temp_no",100)->nullable()->comment('模板编号');
            $table->string("cover_temp_desc",150)->nullable()->comment('描述');
            $table->mediumInteger("cover_temp_photo_count")->nullable()->comment("图片总数");
            $table->mediumInteger("cover_temp_sort")->default(0)->comment("排序");
            $table->string("cover_temp_thumb",200)->nullable()->comment('示意图');
            $table->tinyInteger("cover_temp_check_status")->default(1)->comment('审核状态');
            $table->integer("cover_temp_start_year")->nullable()->comment('起始年份');
            $table->mediumInteger('cover_temp_dpi')->comment('精度')->nullable();
            $table->longText('cover_temp_stage')->nullable()->comment('舞台数据');
            $table->integer('cover_real_page_w')->nullable()->comment('最后一次效果宽');
            $table->integer('cover_real_page_h')->nullable()->comment('最后一次效果高');
            $table->tinyInteger('cover_temp_status')->default('1')->comment('状态');
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
        Schema::dropIfExists('saas_cover_templates');
    }
}
