<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSaasAdPositionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('saas_ad_position', function (Blueprint $table) {
            $table->increments('ad_pos_id')->comment('自增id');
            $table->integer('mch_id')->default('0')->comment('商户id');
            $table->string('ad_position','100')->comment('广告位置');
            $table->string('ad_thumb','255')->comment('示意图');
            $table->mediumInteger('channel_id')->comment('渠道id');
            $table->mediumInteger("ad_status")->default(1)->nullable()->comment("状态");
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
        Schema::dropIfExists('saas_ad_position');
    }
}
