<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSaasArticleTypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('saas_article_type', function (Blueprint $table) {
            $table->increments('art_type_id')->comment('自增id');
            $table->integer('mch_id')->default('0')->comment('商户id')->nullable();
            $table->string('art_type_name','100')->comment('分类名称');
            $table->string('art_type_sign','20')->comment('标识');
            $table->mediumInteger('channel_id')->comment('所属渠道id');
            $table->tinyInteger("art_type_status")->default(1)->nullable()->comment("状态:1启用0禁用");
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
        Schema::dropIfExists('saas_article_type');
    }
}
