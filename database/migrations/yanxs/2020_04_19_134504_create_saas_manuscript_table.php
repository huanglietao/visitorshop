<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSaasManuscriptTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('saas_manuscript', function (Blueprint $table) {
            $table->increments('script_id');
            $table->integer('mch_id')->comment('商户id');
            $table->integer('cha_id')->comment('渠道id');
            $table->integer('user_id')->comment('用户id');
            $table->integer('prod_id')->comment('商品d');
            $table->integer('sku_id')->comment('货品id');
            $table->text('script_url')->comment('原始稿件的url');
            $table->string('prj_file_path')->comment('文件路径');
            $table->integer('prj_page_num')->comment('稿件p数');
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
        Schema::dropIfExists('saas_manuscript');
    }
}
