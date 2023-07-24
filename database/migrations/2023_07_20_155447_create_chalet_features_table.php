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
        Schema::create('chalet_features', function (Blueprint $table) {
            $table->id();
            
            $table->unsignedBigInteger('chalet_id');
            $table->foreign('chalet_id')->references('id')->on('chalets');

            $table->unsignedBigInteger('feature_id');
            $table->foreign('feature_id')->references('id')->on('features');
          
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chalet_features');
    }
};
