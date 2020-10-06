<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLabWorkingHoursTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lab_working_hours', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('lab_id');
            $table->integer('day');
            $table->time('part_from')->nullable();
            $table->time('part_to')->nullable(); 
            $table->boolean('active')->default(0); 
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
        Schema::dropIfExists('lab_working_hours');
    }
}
