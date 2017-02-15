<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TableForm extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('form', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->comment('title');
            $table->unsignedTinyInteger('type')->comment('radio:1.散文 2.诗歌');
            $table->dateTime("time")->comment("记录时间");
            $table->dateTime("notetime")->comment("计划时间");
            $table->string("column")->comment("标签");
            $table->smallInteger("level")->comment("radio");
            $table->string('contents')->comment('contents');
            $table->string('note')->comment('摘要');
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
        Schema::drop('form');
    }
}
