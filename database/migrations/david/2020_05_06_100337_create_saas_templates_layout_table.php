<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSaasTemplatesLayoutTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('saas_templates_layout', function (Blueprint $table) {
            $table->increments('temp_layout_id')->comment('自增id');
            $table->integer('mch_id')->comment('商户id')->default('0');
            $table->string('temp_layout_name','30')->comment('名称');
            $table->mediumInteger("temp_layout_type")->comment('布局类型');
            $table->integer('goods_type_id')->default('0')->comment('产品类型id');
            $table->integer("specifications_id")->comment('规格id');
            $table->tinyInteger('layout_spec_style')->default('1')->comment('规格标签');
            $table->mediumInteger('layout_dpi')->comment('精度')->nullable();
            $table->tinyInteger("layout_check_status")->default(1)->comment('审核状态:1,制作中 2、待审核 3、已审核');
            $table->integer('layout_real_page_w')->nullable()->comment('效果宽');
            $table->integer('layout_real_page_h')->nullable()->comment('效果高');
            $table->mediumInteger('layout_real_dpi')->comment('确定精度')->nullable();
            $table->mediumInteger("temp_layout_sort")->default(0)->comment("排序");
            $table->string("temp_layout_thumb",200)->nullable()->comment('示意图');
            $table->longText('temp_layout_stage')->nullable()->comment('舞台数据');
            $table->mediumInteger("layout_photo_nums")->default(1)->comment("所需照片数量");
            $table->tinyInteger('temp_layout_status')->default('1')->comment('状态');
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
        Schema::dropIfExists('saas_templates_layout');
    }
}
