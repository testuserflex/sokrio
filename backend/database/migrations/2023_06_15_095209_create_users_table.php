<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
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
            $table->string('name');
            $table->string('username');
            $table->integer('contact')->nullable();
            $table->string('email')->unique();
            $table->string('password');
            $table->integer('role')->nullable()->comment('Permission_id');
            $table->boolean('utype')->default(false);
            $table->integer('user_id')->nullable();
            $table->integer('branch_id')->nullable();
            $table->integer('business_id')->nullable();
            $table->integer('initial_visit')->default(0);
            $table->tinyInteger('status')->default(1)->comment('1-actvie,0-deleted,2-suspended');
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
};
