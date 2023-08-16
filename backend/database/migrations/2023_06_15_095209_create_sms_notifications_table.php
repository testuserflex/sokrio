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
        Schema::create('sms_notifications', function (Blueprint $table) {
            $table->integer('id', true);
            $table->bigInteger('business_id');
            $table->bigInteger('branch_id');
            $table->date('date');
            $table->bigInteger('purchase_records');
            $table->bigInteger('purchase_total');
            $table->bigInteger('sale_records');
            $table->bigInteger('sales_total');
            $table->bigInteger('expense_records');
            $table->bigInteger('expenses_total');
            $table->bigInteger('credit_sales');
            $table->bigInteger('arears_total');
            $table->bigInteger('creditors')->nullable();
            $table->bigInteger('stock_value')->nullable();
            $table->string('sent_to_tel', 25)->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sms_notifications');
    }
};
