<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookPlanYearSemesterMonthsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('book_plan_year_semester_months', function (Blueprint $table) {
            $table->id();
            $table->string('semester_month');
            $table->string('hadith_count');
            $table->string('from_hadith');
            $table->string('to_hadith');
            $table->integer('book_plan_year_semester_id');
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
        Schema::dropIfExists('book_plan_year_semester_months');
    }
}
