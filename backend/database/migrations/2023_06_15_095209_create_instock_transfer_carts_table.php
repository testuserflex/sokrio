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
        Schema::create('instock_transfer_carts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->boolean('source')->default(false)->comment('0=shop, 1=store');
            $table->bigInteger('product_id')->index();
            $table->bigInteger('destination')->index()->comment('0=shop, 1=store');
            $table->double('quantity')->nullable();
            $table->bigInteger('unit')->index();
            $table->bigInteger('branch_id')->index();
            $table->bigInteger('business_id')->index();
            $table->bigInteger('user_id')->index();
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
        Schema::dropIfExists('instock_transfer_carts');
    }
};
