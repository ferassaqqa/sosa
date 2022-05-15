<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserExtraDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_extra_data', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('place_id')->nullable();
            $table->string('address')->nullable();
            $table->integer('home_tel')->nullable();
            $table->string('fb_link')->nullable();
            $table->string('collage')->nullable();
            $table->string('speciality')->nullable();
            $table->string('occupation')->nullable();
            $table->string('occupation_place')->nullable();
            $table->string('email')->nullable();
            $table->string('monthly_income')->nullable();
            $table->string('join_date')->nullable();
            $table->string('contract_type')->nullable();
            $table->string('contract_type_value')->nullable();
            $table->string('computer_skills')->nullable();
            $table->string('english_skills')->nullable();
            $table->string('health_skills')->nullable();
            $table->string('qualification')->nullable();
            $table->string('mobile')->nullable();
            $table->string('study_level')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('user_extra_data');
    }
}
