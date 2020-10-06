<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDoctorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('doctors', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('name_ar')->nullable();
            $table->string('name_fr')->nullable();
            $table->enum('gender', array('male', 'famle'))->nullable();
            $table->string('phone')->unique();
            $table->string('sms_code')->nullable();
            $table->string('firebase_token')->nullable();
            $table->string('api_token')->nullable();
            $table->string('email')->unique();
            $table->string('password'); 
            $table->boolean('active')->default(0);
            $table->integer('specialization_id')->nullable();
            $table->integer('degree_id')->nullable();
            $table->string('cv')->nullable();
            $table->string('photo')->default('doctor.png');
            $table->string('reservation_rate')->nullable();
            $table->string('degree_rate')->nullable();
            $table->enum('isHospital', [0, 1])->default(0);


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
        Schema::dropIfExists('doctors');
    }
}
