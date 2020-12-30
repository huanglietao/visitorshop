<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSaasMaterailAttachmentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('saas_materail_attachment', function (Blueprint $table) {
            $table->increments('material_attach_id')->comment('自增id');
            $table->string('material_attach_orig_name')->comment('文件名称')->nullable();
            $table->string('material_attach_path')->comment('存储路径')->nullable();
            $table->string('material_attach_file_name')->comment('新文件名')->nullable();
            $table->double("material_attach_width",8,2)->nullable()->comment('图片宽');
            $table->double("material_attach_height",8,2)->nullable()->comment('图片高');
            $table->double("material_attach_size",8,2)->nullable()->comment('文件大小，以k为单位');
            $table->string('material_attach_uniqid')->comment('同一批次标识')->nullable();
            $table->tinyInteger('material_attach_status')->default('1')->comment('状态');
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
        Schema::dropIfExists('saas_materail_attachment');
    }
}
