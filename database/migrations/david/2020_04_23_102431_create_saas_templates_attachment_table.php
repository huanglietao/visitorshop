<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSaasTemplatesAttachmentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('saas_templates_attachment', function (Blueprint $table) {
            $table->increments('temp_attach_id')->comment('自增id');
            $table->integer('mch_id')->comment('商户id');
            $table->tinyInteger("temp_attach_type")->default('1')->comment('页面类型 1,封面 2,内页 3,主模板')->nullable();
            $table->tinyInteger("temp_attach_material_type")->default('1')->comment('素材类型:1,背景 2,装饰3,边框 4,示意图')->nullable();
            $table->integer('temp_attach_tid')->default('0')->comment('对应的模板id');
            $table->string('temp_attach_orig_name')->comment('附件原')->nullable();
            $table->string('temp_attach_path')->comment('存储路径');
            $table->string('temp_attach_file_name')->comment('新文件名')->nullable();
            $table->double("temp_attach_width",8,2)->nullable()->comment('图片宽');
            $table->double("temp_attach_height",8,2)->nullable()->comment('图片高');
            $table->double("temp_attach_size",8,2)->nullable()->comment('文件大小，以k为单位');
            $table->string('temp_attach_uniqid')->comment('同一批次标识')->nullable();
            $table->tinyInteger('temp_attach_status')->default('1')->comment('状态');
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
        Schema::dropIfExists('saas_templates_attachment');
    }
}
