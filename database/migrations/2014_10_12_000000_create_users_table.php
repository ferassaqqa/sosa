<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->id();//  $2y$10$AkkaBxbUjTNPu92tju1um.OWda2a46achcl8EseNYsrguYun0ZSRi
            $table->string('name');
            $table->string('username')->nullable();
            $table->string('first_name')->nullable();
            $table->string('role')->default(1); // 0 الادارة 1 الطلاب 2 الطالبات
            $table->string('dob')->nullable();
            $table->string('pob')->nullable();
            $table->string('id_num')->unique();
            $table->integer('place_id')->nullable();
            $table->integer('supervisor_area_id')->nullable();
            $table->string('address')->nullable();
            $table->string('prefix')->nullable();
            $table->string('material_status')->nullable();
            $table->string('avatar')->default('logo.png');
            $table->integer('sons_count')->nullable();
            $table->integer('teacher_id')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
