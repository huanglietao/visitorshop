<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOmsCouponNumberTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('oms_coupon_number', function (Blueprint $table) {
            $table->increments('cou_num_id')->comment('自增id');
            $table->integer('cou_id')->comment('优惠券id');
            $table->string('cou_num_code',50)->comment('优惠码');
            $table->decimal('cou_num_money')->comment('面值');
            $table->tinyInteger('cou_num_is_used')->default('1')->comment('是否使用;1:未使用,2:已使用');
            $table->integer('user_id')->comment('使用者id(使用该优惠码的客户id)')->nullable();
            $table->string('order_num',50)->comment('关联订单号(使用该优惠码的订单号)')->nullable();
            $table->integer('cou_num_use_time')->comment('使用时间(该优惠码被使用的时间)')->nullable();
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
        Schema::dropIfExists('oms_coupon_number');
    }
}
