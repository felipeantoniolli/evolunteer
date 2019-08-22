<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVolunteersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('volunteers', function (Blueprint $table) {
            $table->bigIncrements('id_volunteer');
            $table->unsignedBigInteger('id_user');
            $table->string('name', 50);
            $table->string('last_name', 100);
            $table->string('cpf', 11);
            $table->string('rg', 10)->nullable();
            $table->date('birth');
            $table->boolean('gender')->nullable();

            $table->foreign('id_user')->references('id_user')->on('users');
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
        Schema::dropIfExists('volunteers');
    }
}
