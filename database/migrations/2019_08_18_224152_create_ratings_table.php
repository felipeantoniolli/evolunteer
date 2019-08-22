<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRatingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ratings', function (Blueprint $table) {
            $table->bigIncrements('id_rating');
            $table->unsignedBigInteger('id_user');
            $table->unsignedBigInteger('id_volunteer')->nullable();
            $table->unsignedBigInteger('id_institution')->nullable();
            $table->integer('note');
            $table->string('message')->nullable();

            $table->foreign('id_volunteer')->references('id_volunteer')->on('volunteers');
            $table->foreign('id_institution')->references('id_institution')->on('institutions');
            $table->softDeletes();
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
        Schema::dropIfExists('ratings');
    }
}
