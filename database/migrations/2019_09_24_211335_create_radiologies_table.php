<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRadiologiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('radiologies', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('name_ar')->nullable();
            $table->string('name_fr')->nullable();
            $table->string('api_token')->nullable();
            $table->string('phone');
            $table->string('firebase_token')->nullable();
            $table->string('phone2')->nullable();
            $table->integer('city'); 
            $table->integer('area'); 
            $table->string('lang')->nullable();
            $table->string('latt')->nullable();
            $table->string('sms_code')->nullable();
            $table->string('email')->unique();
            $table->string('password'); 
            $table->boolean('active')->default(0);
            $table->enum('delivery', ['1', '0']);
            $table->integer('avaliable_days')->default(0);
            $table->integer('radiology_doctor_id')->nullable(); 
            $table->string('photo')->default('radiology.png');
            $table->enum('reservation_rate', [1,2,3,4,5])->default(1);
            $table->enum('degree_rate', [1,2,3,4,5])->default(1);
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
        Schema::dropIfExists('radiologies');
    }
}
