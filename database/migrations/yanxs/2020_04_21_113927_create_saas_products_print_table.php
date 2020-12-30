<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSaasProductsPrintTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('saas_products_print', function (Blueprint $table) {
            $table->increments('prod_pt_id');
            $table->integer('prod_id')->comment("商品id");
            $table->integer('mch_id')->default(0)->comment("商家id");
            $table->tinyInteger('prod_pt_variable')->default(0)->comment("是否加减P");
            $table->integer('prod_pt_min_p')->default(0)->comment("最小加P数");
            $table->integer('prod_pt_max_p')->default(0)->comment("最大加P数");
            $table->integer('prod_pt_variable_base')->default(0)->comment("加减P基数");
            $table->integer('prod_pt_delivery')->default(0)->comment("交期");
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
        Schema::dropIfExists('saas_products_print');
    }
}
