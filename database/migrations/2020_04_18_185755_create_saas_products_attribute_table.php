<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSaasProductsAttributeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('saas_products_attribute', function (Blueprint $table) {
            $table->increments('attr_id');
            $table->integer("mch_id")->default(0)->comment('商户id');
            $table->integer("cate_id")->default(0)->comment('商品分类id');
            $table->string('attr_name',255)->comment('商品名称');
            $table->tinyInteger('attr_search')->comment('是否可搜索(1:是0：否)');
            $table->tinyInteger('attr_asso')->comment('相同属性值的商品是否关联(1:是0：否)');
            $table->tinyInteger('attr_entry_mode')->comment('录入方式');
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
        Schema::dropIfExists('saas_products_attribute');
    }
}
