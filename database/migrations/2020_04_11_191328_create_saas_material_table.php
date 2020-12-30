<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSaasMaterialTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('saas_material', function (Blueprint $table) {
            $table->increments('material_id')->comment('自增id');
            $table->integer("mch_id")->default(0)->comment('商户id');
            $table->string('material_type',30)->comment("类型标识");
            $table->string("material_name",50)->nullable()->comment('名称');
            $table->integer("material_cateid")->comment('分类id');
            $table->integer("specification_id")->default(0)->comment('规格id');
            $table->integer("attachment_id")->default(0)->comment('附件图片id');
            $table->integer("material_sort")->default(0)->comment('排序');
            $table->tinyInteger('material_status')->default(1)->comment('状态');
            $table->tinyInteger('specification_style')->default(0)->comment('规格标签');
            $table->integer('deleted_at')->nullable()->comment('软删除');
            $table->integer('created_at')->nullable()->comment("创建时间");
            $table->integer("updated_at")->nullable()->comment("更新时间");
            $table->integer("material_use_type")->default(1)->comment('用途');
            $table->integer("material_from_type")->default(0)->comment('来源');
            $table->integer("template_id")->default(0)->comment('模板id');
            $table->integer("material_special_type")->default(0)->comment('特殊元素类型');
            $table->string("material_ext_file",150)->nullable()->comment('其他关联附件');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('saas_material');
    }
}
