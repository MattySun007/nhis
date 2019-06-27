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
            $table->bigIncrements('id');
            $table->string('code', 20);
            $table->string('name', 100);
            $table->string('phone', 15);
            $table->string('email', 125);
            $table->string('address', 125);
            $table->string('rcc_number', 100)->nullable();
            $table->integer('country_id')->unsigned()->nullable()->index();
            $table->integer('state_id')->unsigned()->nullable()->index();
            $table->integer('lga_id')->unsigned()->nullable()->index();
            $table->integer('town_id')->unsigned()->nullable()->index();
            $table->boolean('active')->default('1');
            $table->timestamps();

          $table->unique(['code'], 'duplicate_code');
          $table->unique(['rcc_number'], 'duplicate_rcc_number');
          $table->unique(['email','phone'], 'duplicate_email_phone');
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
        Schema::dropIfExists('institutions');
    }
}
