<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOmsCouponTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('oms_coupon', function (Blueprint $table) {
            $table->increments('cou_id')->comment('自增id');
            $table->integer('mch_id')->comment('商家id');
            $table->integer('sales_chanel_id')->comment('所属子系统id');
            $table->string('goods_id',50)->comment('商品id')->nullable();
            $table->string('goods_category_id',50)->comment('商品id')->nullable();
            $table->tinyInteger('cou_use_limits')->default('1')->comment('使用范围;1:全部商品,2:指定商品,3:指定分类');
            $table->string('cou_name',50)->comment('名称');
            $table->string('cou_desc',120)->comment('描述')->nullable();
            $table->tinyInteger('cou_type')->default('1')->comment('类型;1:卡券,2:优惠码');
            $table->tinyInteger('cou_distribution_method')->default('1')->comment('派送方式;1:用户领取,2:后台发放,3:注册发放,4:积分兑换');
            $table->integer('cou_use_times')->default('0')->comment('使用次数');
            $table->decimal('cou_denomination')->default('0')->comment('面额');
            $table->tinyInteger('cou_use_rule')->default('1')->comment('使用规则;1:无门槛,2:满减');
            $table->decimal('cou_min_consumption')->default('0')->comment('最低消费(使用规则为2时，至少需要达到该额度才可使用优惠)');
            $table->integer('cou_nums')->comment('发放数量');
            $table->integer('cou_score')->comment('最低积分(派送方式为4时，兑换优惠券需达到此积分)')->nullable();
            $table->integer('cou_start_time')->comment('优惠券活动生效时间');
            $table->integer('cou_end_time')->comment('优惠券活动失效时间');
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
        Schema::dropIfExists('oms_coupon');
    }
}
