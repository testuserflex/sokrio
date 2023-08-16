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
        Schema::create('purchase_payments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('purchase_id')->nullable();
            $table->double('amount')->nullable();
            $table->integer('mode')->nullable();
            $table->bigInteger('receipt')->nullable();
            $table->date('date')->nullable();
            $table->integer('recno')->nullable();
            $table->string('month', 2)->nullable();
            $table->year('year')->nullable();
            $table->integer('status')->default(1)->comment('1.Purchase Payment, 2.Previous Creditor');
            $table->integer('branch_id')->nullable();
            $table->integer('business_id')->nullable();
            $table->integer('user_id')->nullable();
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
        Schema::dropIfExists('purchase_payments');
    }
};
