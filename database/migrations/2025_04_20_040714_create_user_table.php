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
        Schema::create('user', function (Blueprint $table) {
            $table->increments('user_id');
            $table->string('user_name', 100)->nullable();
            $table->string('user_name_last', 100)->nullable();
            $table->string('user_email', 150)->unique()->nullable();
            $table->string('user_password', 255)->nullable();
            $table->tinyInteger('user_role')->nullable();
            $table->tinyInteger('user_status')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user');
    }
};
