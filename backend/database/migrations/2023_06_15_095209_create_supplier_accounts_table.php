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
        Schema::create('supplier_accounts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->double('amount_in')->nullable()->default(0);
            $table->double('amount_out')->nullable()->default(0);
            $table->integer('supplier_id')->nullable();
            $table->integer('tId')->nullable()->default(0);
            $table->integer('mode')->nullable();
            $table->integer('refno')->nullable();
            $table->date('date')->nullable();
            $table->text('description')->nullable();
            $table->integer('category')->default(1);
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
        Schema::dropIfExists('supplier_accounts');
    }
};
