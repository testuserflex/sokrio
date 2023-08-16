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
        Schema::create('receipt_settings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('print_receipt_after_sale')->default(1);
            $table->integer('print_receipt_after_purchase')->default(1);
            $table->string('font_type')->nullable();
            $table->double('font_size')->nullable();
            $table->tinyInteger('indicate_website')->nullable()->default(0);
            $table->tinyInteger('indicate_tin')->nullable()->default(0);
            $table->tinyInteger('indicate_business_name')->nullable()->default(1);
            $table->integer('indicate_branch_name')->default(0);
            $table->tinyInteger('indicate_goods_not_returnable')->nullable()->default(0);
            $table->tinyInteger('indicate_goods_vat_inclusive')->nullable()->default(0);
            $table->tinyInteger('indicate_user')->nullable()->default(1);
            $table->tinyInteger('indicate_customer')->nullable()->default(0);
            $table->integer('show_description')->default(0);
            $table->integer('show_pickingdate')->default(0);
            $table->integer('receipt_type')->default(1);
            $table->integer('show_contacts')->default(1);
            $table->integer('print_deposits')->default(0);
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
        Schema::dropIfExists('receipt_settings');
    }
};
