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
        Schema::create('sale_holds', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code', 25)->nullable();
            $table->bigInteger('user_id')->index();
            $table->string('identification', 255)->nullable();
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
        Schema::dropIfExists('sale_holds');
    }
};
