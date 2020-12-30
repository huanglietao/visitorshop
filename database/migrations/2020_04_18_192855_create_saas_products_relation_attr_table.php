<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSaasProductsRelationAttrTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('saas_products_relation_attr', function (Blueprint $table) {
            $table->increments('rel_attr_id');
            $table->integer("product_id")->default(0)->comment('商品id（关联saas_products表）');
            $table->integer("attr_id")->default(0)->comment('属性id（关联saas_products_attribute表）');
            $table->integer('attr_val_id')->comment('属性值id属性id（关联saas_attribute_value表）');
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
        Schema::dropIfExists('saas_products_relation_attr');
    }
}
