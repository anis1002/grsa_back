<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMaterialreservationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
            Schema::create('materialreservations', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->date('reservationdate');
                $table->unsignedBigInteger('material_id')->nullable();
                $table->string('teacher_email');
                $table->foreign('teacher_email')->references('email')->on('teachers')->onDelete('cascade');
                $table->integer('timing');
                $table->foreign('timing')->references('roomtiming')->on('timings')->onDelete('cascade');
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
        Schema::dropIfExists('materialreservations');
    }
}
