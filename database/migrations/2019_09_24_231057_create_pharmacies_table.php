<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePharmaciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pharmacies', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('name_ar')->nullable();
            $table->string('name_fr')->nullable();
            $table->string('phone');
            $table->string('phone2')->nullable();
            $table->string('address');
            $table->string('address_ar')->nullable();
            $table->string('address_fr')->nullable();
            $table->string('lang')->nullable();
            $table->string('api_token')->nullable();
            $table->string('firebase_token')->nullable();
            $table->string('latt')->nullable();
            $table->string('sms_code')->nullable();
            $table->string('email')->unique();
            $table->string('password'); 
            $table->boolean('active')->default(0);
            $table->enum('delivery', ['1', '0'])->default('0');
            $table->integer('avaliable_days')->default(0);
            $table->integer('pharmacy_doctor_id')->nullable();
            $table->string('photo')->default('pharmacy.png');
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
        Schema::dropIfExists('pharmacies');
    }
}
