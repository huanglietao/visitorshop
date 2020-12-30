<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSaasCategoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('saas_category', function (Blueprint $table) {
            $table->increments('cate_id');
            $table->integer('cate_parent_id')->comment("上级分类ID");
            $table->string("cate_all_parent",250)->default('')->comment('父类id集合');
            $table->string('cate_uid',50)->default('')->comment("分类标识");
            $table->string("cate_name",50)->default('')->comment('分类名称');
            $table->string("cate_nickname",50)->default('')->comment('分类别名');
            $table->string("cate_unit",50)->default('')->comment('分类单位');
            $table->string("cate_keywords",50)->default('')->comment('分类关键词');
            $table->string("cate_desc",250)->default('')->nullable()->comment('分类描述');
            $table->tinyInteger('cate_status')->default(1)->comment('分类状态');
            $table->integer('sort')->default(0)->nullable()->comment("排序");
            $table->string('deleted_at',100)->nullable()->comment('分类状态');
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
        Schema::dropIfExists('saas_category');
    }
}
