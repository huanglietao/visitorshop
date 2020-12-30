<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSaasSku2supPriceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('saas_sku2sup_price', function (Blueprint $table) {
            $table->increments('sku_sup_id');
            $table->integer('mch_id')->default(0)->comment('商家id');
            $table->integer('sup_id')->comment('供货商id');
            $table->integer('prod_sku_id')->comment('货品id');
            $table->decimal('sku_sup_price')->comment('供货价格,基准价');
            $table->decimal('addp_price')->comment('每加基准P的价格')->nullable();
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
        Schema::dropIfExists('saas_sku2sup_price');
    }
}
