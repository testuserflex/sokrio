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
        Schema::create('sales', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('customer_id')->nullable();
            $table->double('total')->nullable();
            $table->double('paid')->nullable();
            $table->double('discount')->default(0);
            $table->bigInteger('received')->default(0);
            $table->date('sale_date')->nullable();
            $table->integer('receipt')->nullable();
            $table->string('offline_receipt', 20)->nullable();
            $table->string('picking_date', 100)->nullable();
            $table->integer('picked')->default(0);
            $table->date('date_picked')->nullable();
            $table->boolean('status')->default(true)->comment('0=bad debtor 1=active debtor');
            $table->integer('transaction_status')->default(0);
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
        Schema::dropIfExists('sales');
    }
};
