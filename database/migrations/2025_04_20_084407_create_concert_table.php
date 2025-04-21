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
        Schema::create('concert', function (Blueprint $table) {
            $table->increments('concert_id');
            $table->string('concert_band')->nullable();
            $table->date('concert_date')->nullable();
            $table->time('concert_start')->nullable();
            $table->time('concert_end')->nullable();
            $table->tinyInteger('concert_end_status')->nullable();
            $table->string('concert_location', 255)->nullable();
            $table->integer('concert_price')->nullable();
            $table->integer('concert_remaining_quota')->nullable();
            $table->integer('concert_quota')->nullable();
            $table->integer('concert_category_id')->nullable();
            $table->string('concert_banner')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('concert');
    }
};
