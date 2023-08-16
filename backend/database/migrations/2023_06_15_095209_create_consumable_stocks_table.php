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
        Schema::create('consumable_stocks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('item_id')->nullable();
            $table->bigInteger('unit_id')->nullable();
            $table->double('quantity')->nullable();
            $table->bigInteger('unit_price')->nullable();
            $table->date('date')->nullable();
            $table->integer('user_id')->nullable();
            $table->integer('status')->default(1);
            $table->integer('branch_id')->nullable();
            $table->integer('business_id')->nullable();
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
        Schema::dropIfExists('consumable_stocks');
    }
};
