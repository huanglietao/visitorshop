<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSaasArticleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('saas_article', function (Blueprint $table) {
            $table->increments('art_id')->comment('自增id');
            $table->integer('mch_id')->default('0')->comment('商户id')->nullable();
            $table->string('art_title','100')->comment('文章标题');
            $table->string('art_sign','20')->comment('标识');
            $table->mediumInteger('channel_id')->comment('所属渠道id');
            $table->mediumInteger('art_type')->comment('所属分类id');
            $table->longText('art_content')->comment('文章内容');
            $table->string('art_author','20')->comment('作者');
            $table->string('art_intro','255')->comment('摘要')->nullable();
            $table->string('art_keywords','20')->comment('关键字')->nullable();
            $table->string('author_email','150')->comment('作者邮箱')->nullable();
            $table->string('art_thumb','200')->comment('文章缩略图')->nullable();
            $table->integer('art_views')->default('0')->comment('浏览量')->nullable();
            $table->tinyInteger("is_open")->default(1)->nullable()->comment("是否发布:1发布0未发布");
            $table->tinyInteger("is_read")->default(0)->nullable()->comment("是否已读:1已读，0未读");
            $table->tinyInteger("art_type_status")->default(1)->nullable()->comment("状态:1启用0禁用");
            $table->string('art_link','200')->nullable()->comment('链接地址');
            $table->string('art_file_url','200')->nullable()->comment('附件地址');
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
        Schema::dropIfExists('saas_article');
    }
}
