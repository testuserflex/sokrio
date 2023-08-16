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
        Schema::create('customer_deposits', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('customer_id')->nullable()->index();
            $table->date('date')->nullable();
            $table->double('amount')->default(0);
            $table->bigInteger('mode')->nullable()->index();
            $table->bigInteger('receipt')->nullable();
            $table->bigInteger('user_id')->nullable()->index();
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
        Schema::dropIfExists('customer_deposits');
    }
};
