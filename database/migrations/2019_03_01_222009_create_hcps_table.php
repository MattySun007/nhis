<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHcpsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hcps', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code', 10)->unique();
            $table->string('name', 45);
            $table->string('address', 125);
            $table->string('phone', 15);
            $table->string('email', 125);
            $table->string('account_number', 20)->nullable();
            $table->string('cbn_code', 10)->nullable();
            $table->string('account_name', 100)->nullable();
            $table->tinyInteger('account_type')->default('2');
            $table->integer('country_id')->unsigned()->nullable()->index();
            $table->integer('state_id')->unsigned()->nullable()->index();
            $table->integer('lga_id')->unsigned()->nullable()->index();
            $table->integer('town_id')->unsigned()->nullable()->index();
            $table->boolean('active')->default('1');
            $table->timestamps();

            $table->unique(['phone', 'email'], 'DUPLICATE_HCP');
            $table->foreign('country_id')->references('id')->on('countries')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('state_id')->references('id')->on('states')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('lga_id')->references('id')->on('lgas')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('town_id')->references('id')->on('towns')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hcps');
    }
}
