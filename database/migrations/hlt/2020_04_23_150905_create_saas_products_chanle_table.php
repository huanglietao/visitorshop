<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSaasProductsChanleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('saas_products_chanle', function (Blueprint $table) {
            $table->increments('prod_cha_id');
            $table->integer("cha_id")->default(0)->comment('渠道id');
            $table->integer("prod_id")->default(0)->comment('商品id');
            $table->integer('mch_id')->default(0)->comment('商家id');
            $table->integer("sort")->default(0)->comment('排序');
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
        Schema::dropIfExists('saas_products_chanle');
    }
}
