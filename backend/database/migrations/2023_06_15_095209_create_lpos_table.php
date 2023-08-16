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
        Schema::create('lpos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('products')->nullable();
            $table->bigInteger('supplier')->nullable();
            $table->integer('total');
            $table->bigInteger('paid')->nullable();
            $table->bigInteger('used')->default(0);
            $table->boolean('status')->default(false)->comment('1=approved 0=pending');
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
        Schema::dropIfExists('lpos');
    }
};
