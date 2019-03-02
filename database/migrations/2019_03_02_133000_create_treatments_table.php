<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTreatmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('treatments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('hcp_id')->unsigned()->nullable()->index();
            $table->bigInteger('referer_hcp_id')->unsigned()->nullable()->index();
            $table->bigInteger('user_id')->unsigned()->nullable()->index();
            $table->string('code', 10)->unique();
            $table->decimal('bill', 20, 2);
            $table->string('medical_officer', 100)->nullable();
            $table->longText('diagnosis')->nullable();
            $table->longText('medical_condition')->nullable();
            $table->longText('medication_administered')->nullable();
            $table->boolean('claimed')->default('0');
            $table->timestamps();

            $table->foreign('hcp_id')->references('id')->on('hcps')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('referer_hcp_id')->references('id')->on('hcps')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::dropIfExists('treatments');
    }
}
