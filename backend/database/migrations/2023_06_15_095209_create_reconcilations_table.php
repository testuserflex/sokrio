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
        Schema::create('reconcilations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('num')->default(0);
            $table->integer('type')->default(0);
            $table->bigInteger('user_id')->index();
            $table->bigInteger('branch_id')->index();
            $table->bigInteger('business_id')->index();
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
        Schema::dropIfExists('reconcilations');
    }
};
