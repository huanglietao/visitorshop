<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAttributeValuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('saas_attribute_values', function (Blueprint $table) {
            $table->increments('attr_val_id');
            $table->integer("attr_id")->default(0)->comment('属性id');
            $table->string('attr_val_name',255)->comment('值名称');
            $table->tinyInteger('attr_val_icon')->comment('值图标');
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
        Schema::dropIfExists('saas_attribute_values');
    }
}
