<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSaasSku2custlevelPriceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('saas_sku2custlevel_price', function (Blueprint $table) {
            $table->increments('sku_cust_lv_id');
            $table->integer('mch_id')->default(0)->comment("商家id");
            $table->integer('prod_sku_id')->default(0)->comment("货品id");
            $table->integer('cha_id')->default(0)->comment("渠道id");
            $table->integer('cust_lv_id')->default(0)->comment("用户等级id");
            $table->decimal('sku_cust_lv_price')->default(0)->comment("价格");
            $table->decimal('addp_price')->default(0)->comment("加P价格");
            $table->integer('sort')->default(0)->comment("排序");

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
        Schema::dropIfExists('saas_sku2custlevel_price');
    }
}
