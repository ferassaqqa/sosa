<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->date('start_date')->nullable();
            $table->integer('place_id')->nullable();
            $table->string('course_type')->default('إختبار');
            $table->integer('book_id')->nullable();
            $table->integer('teacher_id')->nullable();
            $table->string('student_category')->nullable();
            $table->integer('hours')->nullable();
            $table->string('included_in_plan')->default('داخل الخطة	');
            $table->string('status')->default('انتظار الموافقة');
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
        Schema::dropIfExists('courses');
    }
}
