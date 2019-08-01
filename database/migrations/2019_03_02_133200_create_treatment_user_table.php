<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTreatmentUserTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('treatment_user', function (Blueprint $table) {
      $table->bigIncrements('id');
      $table->bigInteger('hcp_id')->unsigned()->index();
      $table->bigInteger('user_id')->unsigned()->index();
      $table->string('verification_code', 20)->unique();
      $table->bigInteger('verified_by')->unsigned()->index();
      $table->timestamps();

      $table->foreign('hcp_id')->references('id')->on('hcps')->onDelete('cascade')->onUpdate('cascade');
      $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
      $table->foreign('verified_by')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('treatment_user');
  }
}
