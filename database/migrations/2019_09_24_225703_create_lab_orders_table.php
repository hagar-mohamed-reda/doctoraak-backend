<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLabOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lab_orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('lab_id');
            $table->integer('patient_id');
            $table->string('notes')->nullable();
            $table->string('photo')->default('laborder.png');
            $table->string('insurance_code')->nullable();
            $table->enum('insurance_accept', ['accept', 'refused','required',
                'notrequired',])->default('required');
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
        Schema::dropIfExists('lab_orders');
    }
}
