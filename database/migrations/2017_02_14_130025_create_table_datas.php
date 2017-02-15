<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableDatas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('datas', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('type')->default('A')->commit('type');
            $table->unsignedTinyInteger('rate')->default('5')->commit('1<=rate<=5');
            $table->string('email');
            $table->unsignedTinyInteger('age');
            $table->string('city',10);
            $table->boolean("enabled")->default(0);
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
        Schema::drop('datas');
    }
}
