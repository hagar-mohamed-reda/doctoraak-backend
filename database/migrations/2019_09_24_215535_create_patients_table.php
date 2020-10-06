<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePatientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patients', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('name_ar')->nullable();
            $table->string('name_fr')->nullable();
            $table->string('phone')->nullable();
            $table->string('sms_code')->nullable();
            $table->string('api_token')->nullable();
            $table->string('firebase_token')->nullable();
            $table->string('email')->unique();
            $table->enum('gender', array('male', 'famle'))->default('male');
            $table->string('password'); 
            $table->boolean('active')->default(0);
            $table->integer('insurance_id')->nullable();
            $table->date('birthdate')->nullable();
            $table->string('photo')->default('patient.png');
            $table->string('insurance_code')->nullable();
            $table->string('address');
            $table->string('address_ar')->nullable();
            $table->string('address_fr')->nullable();


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
        Schema::dropIfExists('patients');
    }
}
