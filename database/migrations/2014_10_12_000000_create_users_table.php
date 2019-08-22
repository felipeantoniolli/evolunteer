<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id_user');
            $table->string('email', 50)->unique();
            $table->string('user', 25)->unique();
            $table->string('password');
            $table->string('telephone', 11);
            $table->boolean('type');
            $table->string('cep', 8);
            $table->string('street', 100);
            $table->string('number', 10);
            $table->string('city', 50);
            $table->string('state', 2);
            $table->string('complement')->nullable();
            $table->string('reference')->nullable();
            $table->boolean('active');
            $table->string('secondary_telephone', 11)->nullable();
            $table->string('secondary_email', 50)->unique()->nullable();

            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
