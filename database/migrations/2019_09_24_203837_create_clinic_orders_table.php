<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClinicOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clinic_orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('clinic_id')->nullable();
            $table->integer('patient_id')->nullable();
            $table->integer('part_id')->nullable(); 
            $table->boolean('active')->default(0);
            $table->integer('reservation_number');
            $table->enum('type', array('1', '2', '3'))->nullable();
            $table->string('notes')->nullable(); 
            $table->date('date'); 
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
        Schema::dropIfExists('clinic_orders');
    }
}
