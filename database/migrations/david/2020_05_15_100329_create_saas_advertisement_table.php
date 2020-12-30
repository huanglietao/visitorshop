<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSaasAdvertisementTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('saas_advertisement', function (Blueprint $table) {
            $table->increments('ad_id')->comment('自增id');
            $table->integer('mch_id')->default('0')->comment('商户id');
            $table->string('ad_title','30')->comment('名称');
            $table->mediumInteger('channel_id')->comment('渠道id');
            $table->tinyInteger("ad_type")->default('1')->comment('广告类型');
            $table->mediumInteger("ad_position")->comment('位置说明');
            $table->string("ad_flag",50)->nullable()->comment('广告标识');
            $table->longText('ad_images')->comment('广告图');
            $table->string('ad_url','200')->comment('跳转链接');
            $table->mediumInteger("ad_sort")->default(0)->nullable()->comment("排序");
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
        Schema::dropIfExists('saas_advertisement');
    }
}
