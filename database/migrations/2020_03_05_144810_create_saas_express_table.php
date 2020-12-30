<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSaasExpressTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('saas_express', function (Blueprint $table) {
            $table->increments('express_id')->comment('自增id');
            $table->string("express_name",50)->comment('快递名称')->nullable();
            $table->string ('express_desc',255)->comment('简介')->nullable();
            $table->string("express_logo",255)->comment('快递logo')->nullable();
            $table->string("express_code",20)->comment('快递代码;如yto,sf')->nullable();
            $table->tinyInteger("express_type")->comment('快递类型;1:标准快递,2:商家配送;3:自取')->nullable();
            $table->tinyInteger("express_status")->comment('状态;0:禁用,1:启用')->nullable();
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
        Schema::dropIfExists('saas_express');
    }
}
