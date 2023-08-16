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
        Schema::create('stock_transfer_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->bigInteger('transfer_id')->nullable()->index();
            $table->bigInteger('product_id')->nullable()->index();
            $table->double('quantity_sent')->nullable();
            $table->bigInteger('unit')->nullable()->index('unit');
            $table->double('buying_price')->nullable();
            $table->double('quantity_received')->nullable()->default(0);
            $table->date('receivedOn')->nullable();
            $table->integer('received_by')->nullable();
            $table->string('comment')->nullable();
            $table->boolean('status')->default(false)->comment('1=received 0=pending');
            $table->integer('user_id')->nullable();
            $table->integer('source_branch')->index();
            $table->integer('destination_branch')->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stock_transfer_details');
    }
};
