<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exams', function (Blueprint $table) {
            $table->id();
            $table->string('examable_type')->nullable();
            $table->string('examable_id')->nullable();
            $table->integer('place_id')->nullable();
            $table->text('notes')->nullable();
            $table->string('appointment')->nullable();
            $table->string('date')->nullable();
            $table->string('time')->nullable();
            $table->string('quality_supervisor_id')->nullable();
            $table->integer('status')->default(0);
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
        Schema::dropIfExists('exams');
    }
}
