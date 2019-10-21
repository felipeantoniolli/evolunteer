<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInstitutionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('institutions', function (Blueprint $table) {
            $table->bigIncrements('id_institution');
            $table->unsignedBigInteger('id_user');
            $table->string('reason', 150);
            $table->string('fantasy', 150);
            $table->string('cpf', 14)->unique()->nullable();
            $table->string('cnpj', 18)->unique()->nullable();

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
        Schema::dropIfExists('institutions');
    }
}
