<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOmsSystemSettingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('oms_system_setting', function (Blueprint $table) {
            $table->increments('oms_set_id')->comment("自增id");
            $table->integer('mch_id')->comment("商家id");
            $table->string('oms_name',50)->comment("商户管理平台名称")->nullable();
            $table->string('dms_name',50)->comment("分销管理平台名称")->nullable();
            $table->string('oms_record_num',50)->comment("备案号")->nullable();
            $table->string('oms_copyright',255)->comment("版权信息")->nullable();
            $table->string('oms_linkman',30)->comment("联系人")->nullable();
            $table->string('oms_mobile',20)->comment("联系方式")->nullable();
            $table->string('oms_address',30)->comment("联系地址")->nullable();
            $table->integer('oms_balance_reminder')->comment("余额提醒阈值")->nullable();
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
        Schema::dropIfExists('oms_system_setting');
    }
}
