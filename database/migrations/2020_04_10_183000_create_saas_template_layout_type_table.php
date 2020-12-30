<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSaasTemplateLayoutTypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('saas_template_layout_type', function (Blueprint $table) {
            $table->increments('temp_layout_type_id')->comment('自增id');
            $table->integer("mch_id")->default(0)->comment('商户id');
            $table->string('temp_layout_type_name',30)->comment("版式名称");
            $table->string("temp_layout_type_intro",150)->nullable()->comment('简介');
            $table->tinyInteger('temp_layout_type_status')->default(1)->comment('状态');
            $table->integer('deleted_at')->nullable()->comment('软删除');
            $table->integer('created_at')->nullable()->comment("创建时间");
            $table->integer("updated_at")->nullable()->comment("更新时间");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('saas_template_layout_type');
    }
}
