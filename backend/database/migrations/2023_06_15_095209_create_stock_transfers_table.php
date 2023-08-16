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
        Schema::create('stock_transfers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('products')->nullable();
            $table->bigInteger('source_branch')->nullable();
            $table->bigInteger('destination_branch')->nullable();
            $table->boolean('source_type')->nullable()->default(false)->comment('0=shop 1=store');
            $table->date('sentOn')->nullable();
            $table->bigInteger('business_id')->nullable();
            $table->bigInteger('sent_by')->index();
            $table->boolean('status')->default(false)->comment('0=pending 1=received');
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
        Schema::dropIfExists('stock_transfers');
    }
};
