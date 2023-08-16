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
        Schema::create('cashins', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->double('amount')->nullable();
            $table->integer('mode')->nullable();
            $table->integer('refno')->nullable();
            $table->string('broughtBy')->nullable();
            $table->date('date')->nullable();
            $table->text('reason')->nullable();
            $table->integer('branch_id')->nullable();
            $table->integer('business_id')->nullable();
            $table->integer('user_id')->nullable();
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
        Schema::dropIfExists('cashins');
    }
};
