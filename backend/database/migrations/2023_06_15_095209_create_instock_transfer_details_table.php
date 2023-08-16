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
        Schema::create('instock_transfer_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('transfer_id')->nullable()->index();
            $table->bigInteger('product_id')->nullable()->index();
            $table->double('quantity_sent')->nullable();
            $table->bigInteger('unit')->index();
            $table->double('buying_price')->nullable();
            $table->string('comment')->nullable();
            $table->boolean('status')->default(false);
            $table->integer('user_id')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('instock_transfer_details');
    }
};
