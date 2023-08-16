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
        Schema::create('batches', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('batch_number')->nullable();
            $table->date('expiry_date')->nullable();
            $table->double('quantity_in')->nullable();
            $table->double('quantity_out')->nullable();
            $table->bigInteger('product_id')->nullable()->index();
            $table->bigInteger('branch_id')->nullable()->index();
            $table->bigInteger('business_id')->nullable()->index();
            $table->bigInteger('user_id')->nullable()->index();
            $table->tinyInteger('is_expired')->default(0);
            $table->boolean('type')->default(false);
            $table->double('quantity_expired')->nullable();
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
        Schema::dropIfExists('batches');
    }
};
