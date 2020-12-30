<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSassAreasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sass_areas', function (Blueprint $table) {
            $table->increments('area_id');
            $table->string("area_name",30)->default('')->comment('区域名称');
            $table->integer("pid")->comment('区域上级标识');
            $table->string("short_name",30)->default('')->comment('区域简称');
            $table->integer("level")->comment('区域等级');
            $table->string("area_code",20)->default('')->comment('区域编码');
            $table->string("zip_code",20)->default('')->comment('邮政编码');
            $table->string("comb_name",150)->default('')->comment('组合名称');
            $table->softDeletes();
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
        Schema::dropIfExists('sass_areas');
    }
}
