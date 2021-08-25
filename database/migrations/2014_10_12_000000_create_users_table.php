<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username', 64)->unique();
            $table->string('password', 128);
            $table->string('name', 64);
            $table->string('email', 128)->unique();
            $table->string('avatar')->default('avatars/default.jpg');
            $table->integer('rating')->default(0);
            $table->enum('role', ['user' , 'admin'])->default('user');
            $table->longText('remember_token')->nullable();
            $table->timestamps();
        });
    }
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
