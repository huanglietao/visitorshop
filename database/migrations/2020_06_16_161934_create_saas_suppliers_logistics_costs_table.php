<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSaasSuppliersLogisticsCostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('saas_suppliers_logistics_costs', function (Blueprint $table) {
            $table->increments('sup_log_cos_id')->comment('自增id');
            $table->integer('sup_id')->comment('供货商id');
            $table->string('sup_log_cos_delivery_list',255)->comment('包含配送方式列表')->nullable();
            $table->text('sup_log_cos_area_conf')->comment('区域运费成本配置')->nullable();
            $table->integer('created_at')->comment('创建时间')->nullable();
            $table->integer('updated_at')->comment('更新时间')->nullable();
            $table->integer('deleted_at')->comment('删除时间')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('saas_suppliers_logistics_costs');
    }
}
