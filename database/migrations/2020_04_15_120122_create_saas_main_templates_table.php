<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSaasMainTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('saas_main_templates', function (Blueprint $table) {
            $table->increments('main_temp_id')->comment('自增id');
            $table->integer("mch_id")->default(0)->comment('商户id');
            $table->string('main_temp_name',50)->comment("模板名称");
            $table->integer("inner_temp_id")->default(0)->comment('关联内页模板id');
            $table->integer("cover_temp_id")->default(0)->comment('关联封面模板id');
            $table->integer("goods_type_id")->comment('产品类型id');
            $table->integer("main_temp_theme_id")->comment('模板主题分类id');
            $table->integer("specifications_id")->comment('规格id');
            $table->string("main_temp_description",150)->nullable()->comment('描述');
            $table->mediumInteger("main_temp_sort")->default(0)->comment("排序");
            $table->string("main_temp_thumb",200)->nullable()->comment('示意图');
            $table->mediumInteger("main_temp_photo_count")->nullable()->comment("图片总页数");
            $table->tinyInteger('main_temp_status')->default(1)->comment('状态');
            $table->tinyInteger('main_temp_is_vip')->default(0)->comment('是否为vip模板');
            $table->tinyInteger("main_temp_check_status")->default(1)->comment('审核状态');
            $table->tinyInteger("main_temp_is_ads_display")->default(0)->comment('是否可作为告展示');
            $table->integer("main_temp_start_year")->nullable()->comment('起始年份');
            $table->string("temp_tag",100)->nullable()->comment('关联模板标签');
            $table->mediumInteger("main_temp_min_photo")->nullable()->comment("最少容纳照片数");
            $table->mediumInteger("main_temp_max_photo")->nullable()->comment("最多容纳照片数");
            $table->string("main_temp_no",100)->nullable()->comment('模板编号');
            $table->integer("main_temp_use_times")->default(0)->nullable()->comment('使用次数');
            $table->double("main_temp_avg_photo",6,2)->nullable()->comment('关联模板标签');
            $table->integer('deleted_at')->nullable()->comment('软删除');
            $table->integer('created_at')->nullable()->comment("创建时间");
            $table->integer("updated_at")->nullable()->comment("更新时间");

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('saas_main_templates');
    }
}
