<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePharmacyOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pharmacy_orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('pharmacy_id')->nullable();
            $table->integer('patient_id');
            $table->string('notes')->nullable();
            $table->string('photo')->default('pharmacyorder.png');
           $table->enum('insurance_accept', ['accept', 'refused','required',
                'notrequired',])->default('required');
            $table->string('insurance_code')->nullable();
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
        Schema::dropIfExists('pharmacy_orders');
    }
}
