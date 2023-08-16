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
        Schema::create('reconcilation_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('product_id')->nullable()->index();
            $table->bigInteger('unit_id')->nullable()->index();
            $table->integer('stockreconciliation_id');
            $table->bigInteger('base_quantity')->nullable()->index();
            $table->bigInteger('actual_quantity')->nullable()->index();
            $table->integer('type')->default(0);
            $table->bigInteger('branch_id')->nullable()->index();
            $table->bigInteger('business_id')->nullable()->index();
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
        Schema::dropIfExists('reconcilation_logs');
    }
};
