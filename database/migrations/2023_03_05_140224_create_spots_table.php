<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSpotsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('spots', function (Blueprint $table) {
            $table->id();
            $table->integer('users_id');
            $table->string('spots_name');
            $table->string('spots_address');
            $table->string('spots_latitude');
            $table->string('spots_longitude');
            $table->string('spots_url');
            $table->string('spots_status');
            $table->integer('spots_max');
            $table->integer('spots_count');
            $table->string('spots_day_count');
            $table->string('spots_month_count');
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
        Schema::dropIfExists('spots');
    }
}
