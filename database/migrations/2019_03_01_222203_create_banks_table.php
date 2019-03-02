<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBanksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('banks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('cbn_code', 10)->unique();
            $table->string('cbn_code_main', 6)->nullable();
            $table->string('bank_type', 45);
            $table->string('bank_name', 100);
            $table->timestamps();
            $table->unique(['bank_type','bank_name'], 'DUPLICATE_BANK_INFO');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('banks');
    }
}
