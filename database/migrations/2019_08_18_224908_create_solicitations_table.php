<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSolicitationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('solicitations', function (Blueprint $table) {
            $table->bigIncrements('id_solicitation');
            $table->unsignedBigInteger('id_volunteer');
            $table->unsignedBigInteger('id_institution');
            $table->string('message')->nullable();
            $table->boolean('approved')->nullable()->default(0);
            $table->string('justification')->nullable();

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
        Schema::dropIfExists('solicitations');
    }
}
