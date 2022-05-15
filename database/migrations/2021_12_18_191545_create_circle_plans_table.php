<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCirclePlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('circle_plans', function (Blueprint $table) {
            $table->id();
            $table->string('year')->nullable();
            $table->string('book_id')->nullable();
            $table->string('guaranteed_yearly')->nullable();
            $table->string('guaranteed_semesterly')->nullable();
            $table->string('guaranteed_monthly')->nullable();
            $table->string('volunteer_yearly')->nullable();
            $table->string('volunteer_semesterly')->nullable();
            $table->string('volunteer_monthly')->nullable();
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
        Schema::dropIfExists('circle_plans');
    }
}
