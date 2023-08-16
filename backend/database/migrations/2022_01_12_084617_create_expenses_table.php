<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExpensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->date('date')->nullable();
            $table->double('amount')->nullable();
            $table->text('description')->nullable();
            $table->Integer('category_id')->nullable();
            $table->Integer('mode_id')->nullable();
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
        Schema::dropIfExists('expenses');
    }
}
