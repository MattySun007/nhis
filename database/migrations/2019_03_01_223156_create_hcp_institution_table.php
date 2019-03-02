<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHcpInstitutionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hcp_institution', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('hcp_id')->unsigned()->index();
            $table->bigInteger('institution_id')->unsigned()->index();
            $table->timestamps();

            $table->unique(['hcp_id', 'institution_id'], 'DUPLICATE_RECORD');
            $table->foreign('hcp_id')->references('id')->on('hcps')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('institution_id')->references('id')->on('institutions')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hcp_institution');
    }
}
