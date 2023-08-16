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
        Schema::create('debtors', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('customer_id');
            $table->bigInteger('total');
            $table->bigInteger('paid');
            $table->bigInteger('discount')->default(0);
            $table->date('date');
            $table->integer('user_id');
            $table->integer('branch_id');
            $table->integer('business_id');
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
        Schema::dropIfExists('debtors');
    }
};
