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
        Schema::create('invoices', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('customer_id')->nullable();
            $table->double('total')->nullable();
            $table->date('date')->nullable();
            $table->integer('recno')->nullable();
            $table->integer('month')->nullable();
            $table->year('year')->nullable();
            $table->bigInteger('invoice_number')->nullable();
            $table->boolean('status')->default(false)->comment('0=Pending 1= Approved');
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
        Schema::dropIfExists('invoices');
    }
};
