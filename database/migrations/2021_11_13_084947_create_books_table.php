<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('hadith_count')->nullable();
            $table->string('hours_count')->nullable();
            $table->string('pass_mark')->nullable();
            $table->string('required_students_number_array')->nullable();
            $table->string('required_students_number')->nullable();
            $table->string('book_code')->nullable();
            $table->integer('department')->default(0);
            $table->string('student_category')->nullable();
            $table->integer('year')->nullable();
            $table->integer('included_in_plan')->default(1);
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
        Schema::dropIfExists('books');
    }
}
