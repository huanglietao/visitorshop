<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSaasSalaryCalculationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('saas_salary_calculation', function (Blueprint $table) {
            $table->increments('salary_calc_id');
            $table->integer("salary_detail_id")->default(0)->comment('所属详情id');
            $table->string('workers_name',255)->default('')->comment('姓名');
            $table->integer("salary_worker_position")->default(0)->comment('职位,取config->salary中配置');
            $table->integer("shift")->nullable()->comment('班次');
            $table->integer("finish_time")->nullable()->comment('日期');
            $table->integer("output_totals")->nullable()->comment('总产出数量');
            $table->decimal("univalence",10,2)->nullable()->comment('单价');
            $table->decimal("salary",10,2)->nullable()->comment('工资');
            $table->integer('created_at')->nullable()->comment('创建时间')->nullable();
            $table->integer('updated_at')->nullable()->comment('更新时间')->nullable();
            $table->integer('deleted_at')->nullable()->comment('删除时间')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('saas_salary_calculation');
    }
}
