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
        Schema::create('chalets', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone');
            $table->text('about')->nullable();

            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');

            $table->unsignedBigInteger('image_id')->nullable();
            $table->foreign('image_id')->references('id')->on('images');

            $table->integer('reviews_count')->default(0);
            $table->integer('review')->default(0);
            $table->integer('views')->default(0);
            $table->integer('max_number')->default(0);
            $table->integer('balance')->default(0);
            $table->string('day_time')->nullable();
            $table->string('night_time')->nullable();
            $table->string('facebook')->nullable();
            $table->string('instagram')->nullable();
            $table->string('video')->nullable();
            $table->string('address')->nullable();
            $table->decimal("latitude", 10, 8)->nullable();
            $table->decimal("longitude", 11, 8)->nullable();
            $table->boolean('is_blocked')->default(false);
            $table->boolean('is_approved')->default(false);
            $table->integer('down_payment_percent')->default(0);

            $table->unsignedBigInteger('city_id')->nullable();
            $table->foreign('city_id')->references('id')->on('cities');

            $table->unsignedBigInteger('country_id')->nullable();
            $table->foreign('country_id')->references('id')->on('countries');

            $table->unsignedBigInteger('user_id_image_id')->nullable();
            $table->foreign('user_id_image_id')->references('id')->on('images');

            $table->unsignedBigInteger('license')->nullable();
            $table->foreign('license')->references('id')->on('images');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chalets');
    }
};
