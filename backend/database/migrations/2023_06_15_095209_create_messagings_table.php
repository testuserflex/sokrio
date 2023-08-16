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
        Schema::create('messagings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('sender')->nullable()->index();
            $table->bigInteger('receiver')->nullable()->index();
            $table->string('message_type')->nullable();
            $table->string('message')->nullable();
            $table->integer('size')->nullable()->index();
            $table->bigInteger('message_id')->nullable()->index();
            $table->bigInteger('branch_id')->nullable()->index();
            $table->bigInteger('business_id')->nullable()->index();
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
        Schema::dropIfExists('messagings');
    }
};
