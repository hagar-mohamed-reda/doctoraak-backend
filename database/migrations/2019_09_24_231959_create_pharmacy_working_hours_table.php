<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePharmacyWorkingHoursTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pharmacy_working_hours', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('pharmacy_id')->nullable();
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
        Schema::dropIfExists('pharmacy_working_hours');
    }
}
