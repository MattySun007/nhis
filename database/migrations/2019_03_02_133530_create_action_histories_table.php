<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActionHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('action_histories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('institution_id')->unsigned()->nullable()->index();
            $table->bigInteger('user_id')->unsigned()->nullable()->index();
            $table->string('profile', 50);
            $table->string('action', 100);
            $table->longText('data');
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
        Schema::dropIfExists('action_histories');
    }
}
