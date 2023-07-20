<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('chalet_pricings', function (Blueprint $table) {
            $table->id();


            $table->unsignedBigInteger('chalet_id');
            $table->foreign('chalet_id')->references('id')->on('chalets');

            $table->integer('sunday_to_wednesday_day');
            $table->integer('sunday_to_wednesday_night');
            $table->integer('saturday_and_thursday_day');
            $table->integer('saturday_and_thursday_night');
            $table->integer('friday_day');
            $table->integer('friday_night');

            $table->integer('full_day_extra_price');

           
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chalet_pricings');
    }
};
