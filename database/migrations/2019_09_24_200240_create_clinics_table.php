<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClinicsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clinics', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('photo')->default('clinic.png');
            $table->string('phone')->nullabe();
            $table->string('fees'); 
            $table->string('city');
            $table->string('area'); 
            $table->string('lang')->nullable();
            $table->string('latt')->nullable();
            $table->integer('waiting_time')->default(0); 
            $table->boolean('active')->default(0); 
            $table->boolean('availability')->default(1);
            $table->integer('doctor_id')->nullable();
            $table->integer('available_days')->default(0);

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
        Schema::dropIfExists('clinics');
    }
}
