<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePatientRatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patient_rates', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('doctor_id')->nullable();
            $table->integer('patient_id')->nullable();
            $table->enum('rate', [1,2,3,4,5])->default(1);
             $table->enum('type', ['PATIENT','DOCTOR'])->default('PATIENT');
           
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
        Schema::dropIfExists('patient_rates');
    }
}
