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
            $table->bigIncrements('id');
            $table->string('first_name', 50)->nullable();
            $table->string('middle_name', 50)->nullable();
            $table->string('last_name', 50)->nullable();
            $table->string('email', 125)->unique();
            $table->string('phone', 15)->unique();
            $table->string('password');
            $table->timestamp('email_verified_at')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('colour', 50)->nullable();
            $table->tinyInteger('height')->nullable();
            $table->tinyInteger('marital_status_id')->unsigned()->nullable()->index();
            $table->tinyInteger('genotype_id')->unsigned()->nullable()->index();
            $table->tinyInteger('blood_group_id')->unsigned()->nullable()->index();
            $table->tinyInteger('gender_id')->unsigned()->nullable()->index();
            $table->boolean('status')->default('1');
            $table->decimal('contribution_amount', 20, 2)->default('0.0');
            $table->rememberToken();
            $table->timestamps();

            $table->foreign('marital_status_id')->references('id')->on('marital_statuses')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('genotype_id')->references('id')->on('genotypes')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('blood_group_id')->references('id')->on('blood_groups')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('gender_id')->references('id')->on('genders')->onDelete('cascade')->onUpdate('cascade');
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
