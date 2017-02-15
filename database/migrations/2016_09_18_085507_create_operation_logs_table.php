<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOperationLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('operation_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('admin_id')->index();
            $table->string('route'); //
            $table->string('path'); //
            $table->unsignedTinyInteger("method");
            $table->text('data')->nullable(); //操作相关的数据备份
            $table->text('extra')->nullable(); //操作相关的数据备份
            $table->ipAddress('ip'); //操作人员IP地址
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('operation_logs');
    }
}
