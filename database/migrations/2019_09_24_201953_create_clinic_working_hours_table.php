<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClinicWorkingHoursTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clinic_working_hours', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('clinic_id')->nullable();
            $table->integer('day');
            $table->time('part1_from')->nullable();;
            $table->time('part1_to')->nullable();;
            $table->time('part2_from')->nullable();
            $table->time('part2_to')->nullable(); 
            $table->boolean('active')->default(0);
            $table->integer('reservation_number_1');
            $table->integer('reservation_number_2');
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
        Schema::dropIfExists('clinic_working_hours');
    }
}
