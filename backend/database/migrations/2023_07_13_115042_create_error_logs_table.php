<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateErrorLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('error_logs', function (Blueprint $table) {

                if (!Schema::hasTable('error_logs')) {
            return;
        }
            $table->bigIncrements('id');
            $table->string('error');
            $table->integer('user_id');
            $table->integer('branch_id');
            $table->integer('business_id');
            $table->tinyInteger('status')->default(1)->comment('1-actvie,0-deleted');
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
        Schema::dropIfExists('error_logs');
    }
}
