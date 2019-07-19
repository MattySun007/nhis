<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContributionsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('contributions', function (Blueprint $table) {
      $table->bigIncrements('id');
      $table->string('batch_code', 20);
      $table->bigInteger('user_id')->unsigned()->index();
      $table->decimal('amount', 20, 2);
      $table->tinyInteger('month')->unsigned()->nullable();
      $table->integer('year')->unsigned()->nullable();
      $table->boolean('processed')->default('0');
      $table->bigInteger('processed_by')->unsigned()->nullable();
      $table->dateTime('processed_at')->nullable();
      $table->boolean('approved')->default('0');
      $table->bigInteger('approved_by')->unsigned()->nullable();
      $table->dateTime('approved_at')->nullable();
      $table->timestamps();

      $table->unique(['user_id', 'month', 'year'], 'DUPLICATE_RECORD');
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
    Schema::dropIfExists('contributions');
  }
}
