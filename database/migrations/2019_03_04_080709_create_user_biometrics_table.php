<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserBiometricsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_biometrics', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->unsigned()->index()->unique();
          $table->string('class_id', 150)->index();
          $table->string('token_id', 150)->index();
          $table->string('data_url', 255)->nullable();
          $table->boolean('biometric_status')->default('0');
            $table->timestamps();
          $table->unique(['class_id','token_id'], 'duplicate_class_token');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_biometrics');
    }
}
