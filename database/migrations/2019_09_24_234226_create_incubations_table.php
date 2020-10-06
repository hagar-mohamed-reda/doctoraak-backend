<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIncubationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('incubations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('name_ar')->nullable();
            $table->string('name_fr')->nullable();
            $table->string('description')->nullable();
            $table->string('description_ar')->nullable();
            $table->string('description_fr')->nullable();
            $table->integer('city');
            $table->integer('area');
            $table->double('lng')->nullable();
            $table->double('lat')->nullable();
            $table->integer('bed_number')->default(0);
            $table->enum('rate', [1, 2, 3, 4, 5])->default('1');
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
        Schema::dropIfExists('incubations');
    }
}
