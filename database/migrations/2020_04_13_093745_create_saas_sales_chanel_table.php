<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSaasSalesChanelTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('saas_sales_chanel', function (Blueprint $table) {
            $table->increments('cha_id')->comment('自增id');
            $table->string('cha_name',50)->comment('渠道名称');
            $table->tinyInteger('cha_flag')->comment('渠道标识;所面对的终端用户1分销 2会员');
            $table->string('cha_desc',255)->comment('渠道描述');
            $table->integer('sort')->comment('排序');
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
        Schema::dropIfExists('saas_sales_chanel');
    }
}
