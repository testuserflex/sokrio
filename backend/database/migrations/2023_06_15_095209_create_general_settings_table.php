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
        Schema::create('general_settings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->boolean('back_date_sales')->default(false);
            $table->boolean('back_date_purchases')->default(false);
            $table->boolean('sale_holding')->default(false);
            $table->boolean('allow_negative_stock')->default(false);
            $table->tinyInteger('print_order_invoices')->default(0);
            $table->boolean('allow_multiple_units')->default(false);
            $table->integer('track_expiries')->default(0)->comment('0=inactive 1=active');
            $table->boolean('allow_reserve_price')->default(false);
            $table->boolean('track_untake_orders')->default(false);
            $table->integer('customer_deposit')->default(0)->comment('0=inactive 1=active');
            $table->boolean('track_customers')->default(false);
            $table->integer('track_suppliers')->default(0)->comment('0=inactive 1=active');
            $table->integer('import_products')->default(0);
            $table->integer('send_appreciation')->default(0);
            $table->string('customer_message', 1000)->nullable();
            $table->integer('message_saledetails')->default(0);
            $table->integer('enable_credit_limit')->default(0);
            $table->integer('enable_debt_limit')->default(0);
            $table->integer('confirm_readyorders')->default(0);
            $table->integer('enable_wholeselling')->default(0);
            $table->integer('stockvalue_calculation')->default(0);
            $table->integer('sale_description')->default(0);
            $table->integer('negative_stocktransfer')->default(0);
            $table->integer('show_branchname')->default(0);
            $table->integer('set_total_as_paid')->default(1);
            $table->integer('sale_discount')->default(0);
            $table->integer('purchase_discount')->default(0);
            $table->integer('sellingprice_onstocking')->default(0);
            $table->integer('autofillprices_onstocking')->default(0);
            $table->integer('cash_at_hand')->default(0);
            $table->integer('show_businessvalue')->default(0);
            $table->integer('show_batch_selling')->default(0);
            $table->integer('update_receivedproduct')->default(0);
            $table->integer('deposit_balances')->default(0);
            $table->integer('use_last_costprice')->default(0);
            $table->integer('branch_id')->nullable();
            $table->integer('business_id')->nullable();
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
        Schema::dropIfExists('general_settings');
    }
};
