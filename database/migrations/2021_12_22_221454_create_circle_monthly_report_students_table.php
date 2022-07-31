<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCircleMonthlyReportStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('circle_monthly_report_students', function (Blueprint $table) {
            $table->id();
            $table->integer('circle_monthly_report_id')->nullable();
            $table->integer('student_id')->nullable();
            $table->string('previous_from')->nullable();
            $table->string('previous_to')->nullable();
            $table->string('current_from')->nullable();
            $table->string('current_to')->nullable();
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
        Schema::dropIfExists('circle_monthly_report_students');
    }
}
