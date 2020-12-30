<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSaasProductSkuTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('saas_product_sku', function (Blueprint $table) {
            $table->increments('prod_sku_id')->comment('自增id');
            $table->integer('prod_id')->comment('商品id');
            $table->string('prod_attr_comb',258)->comment('关联属性组合（商品关联属性表的id）');
            $table->string('prod_sku_sn',50)->comment('货号');
            $table->string('prod_process_code',255)->comment('工艺码');
            $table->decimal("prod_sku_price",10,2)->comment('销售单价');
            $table->decimal("prod_sku_cost",10,2)->comment('货品成本价');
            $table->integer('prod_sku_weight')->comment('货品重量（单位为克）');
            $table->string('prod_sku_addp_info',255)->comment('加P的客外信息（例：销售价|成本价|重量【指的是单一P的组合计算需要跟saas_products_print:prod_pt_variable_base来计算】）');
            $table->tinyInteger("prod_sku_onsale_status")->comment('开卖状态(1：开卖；2：未开卖)');
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
        Schema::dropIfExists('saas_product_sku');
    }
}
