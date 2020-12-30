<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSaasProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('saas_projects', function (Blueprint $table) {
            $table->increments('prj_id');
            $table->integer('mch_id')->comment('商户id');
            $table->integer('cha_id')->comment('渠道id');
            $table->integer('user_id')->comment('用户id');
            $table->integer('prod_id')->comment('商品d');
            $table->integer('sku_id')->comment('货品id');
            $table->string('prj_image')->comment('封面缩略图');
            $table->string('prj_images_path')->comment('作品引用图片OSS存储根目录');
            $table->tinyInteger("prj_file_status")->default('1')->comment('0：已关闭；1：打开中【只有状态为0才能订购】')->nullable();
            $table->tinyInteger("prj_status")->default('1')->comment('1：制作中；2：待确认；3：已订购；4：回收站')->nullable();
            $table->string('prj_file_path')->comment('文件路径');
            $table->integer('prj_page_num')->comment('作品p数');
            $table->integer('sort')->comment('排序');
            $table->integer('prj_tpl_id')->comment('模板id');
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
        Schema::dropIfExists('saas_projects');
    }
}
