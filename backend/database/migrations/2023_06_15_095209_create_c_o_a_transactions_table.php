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
        Schema::create('c_o_a_transactions', function (Blueprint $table) {
            $table->integer('id', true);
            $table->bigInteger('account');
            $table->bigInteger('reference');
            $table->bigInteger('reference_id');
            $table->date('date');
            $table->bigInteger('dr')->default(0);
            $table->bigInteger('cr')->default(0);
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
        Schema::dropIfExists('c_o_a_transactions');
    }
};
