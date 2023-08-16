<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->Integer('sale_id')->nullable();
            $table->double('amount')->nullable();
            $table->Integer('mode')->nullable();
            $table->bigInteger('receipt')->nullable();
            $table->date('date')->nullable();
            $table->Integer('recno')->nullable();
            $table->Integer('month')->nullable();
            $table->year('year')->nullable();
            $table->Integer('branch_id')->nullable();
            $table->Integer('business_id')->nullable();
            $table->Integer('user_id')->nullable();
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
        Schema::dropIfExists('payments');
    }
}
