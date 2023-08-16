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
        Schema::create('instock_transfers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('products')->nullable();
            $table->bigInteger('source')->nullable();
            $table->bigInteger('destination')->nullable();
            $table->date('sentOn')->nullable();
            $table->bigInteger('branch_id')->nullable();
            $table->bigInteger('business_id')->nullable();
            $table->bigInteger('sent_by')->index();
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
        Schema::dropIfExists('instock_transfers');
    }
};
