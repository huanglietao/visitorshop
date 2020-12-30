<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSaasProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('saas_products', function (Blueprint $table) {
            $table->increments('prod_id');
            $table->integer('mch_id')->default('0')->comment('商户id');
            $table->integer('prod_cate_uid')->comment('分类id');
            $table->integer('mch_prod_cate_uid')->comment('商家自定义分类id');
            $table->string("prod_name",255)->comment('商品名称');
            $table->string("prod_title",255)->comment('副标题')->nullable();
            $table->string("prod_sn",50)->comment('商品编码')->nullable();
            $table->string("prod_code",50)->comment('商品条形码')->nullable();
            $table->decimal("prod_fee",10,2)->comment('商品价格');
            $table->string("prod_label",20)->comment('商品标签')->nullable();
            $table->string("prod_unit",20)->comment('计量单位')->nullable();
            $table->tinyInteger("prod_stock_status")->default(1)->comment('库存启用状态(1:启用,2:禁用)');
            $table->integer("prod_stock_inventory")->default('0')->comment('库存量');
            $table->integer("prod_stock_waring")->default('0')->comment('库存预警值');
            $table->mediumInteger("prod_brand_id")->comment('品牌id');
            $table->tinyInteger("prod_express_type")->comment('物流方式(1：固定收取物流费；2：按快递模板)');
            $table->decimal("prod_express_fee",10,2)->comment('物流费用(仅存固定收取的物流费用)');
            $table->integer("prod_express_tpl_id")->comment('物流模板id(关联sass_delivery_template)');
            $table->text("prod_details_pc")->comment('pc商品详情')->nullable();
            $table->text("prod_details_mobile")->comment('移动端商品详情')->nullable();
            $table->string("prod_return_flag",50)->comment('退货标识')->nullable();
            $table->string("prod_comment_flag",50)->comment('评论标识')->nullable();
            $table->tinyInteger("prod_onsale_status")->default('0')->comment('开卖状态(1,上架 0下架)');
            $table->tinyInteger("prod_onsale_issingle")->default('1')->comment('单独销售(1,是 0否)');
            $table->tinyInteger("prod_examine_status")->default('1')->comment('审核状态(0,无需审核 1审核成功 2审核失败)');
            $table->tinyInteger("prod_price_type")->default('1')->comment('定价方式(1SPU  2SKU)');
            $table->integer("prod_integral_sale")->default('0')->comment('消费积分');
            $table->integer("prod_integral_level")->default('0')->comment('等级积分');
            $table->string("prod_keywords",100)->comment('关键词')->nullable();
            $table->string("prod_remark",255)->comment('商家备注')->nullable();
            $table->integer("sort")->default('0')->comment('商家备注');
            $table->tinyInteger('deleted_at')->default(0)->nullable()->comment('软删除');
            $table->integer('created_at')->comment("创建时间");
            $table->integer("updated_at")->comment("更新时间");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('saas_products');
    }
}
