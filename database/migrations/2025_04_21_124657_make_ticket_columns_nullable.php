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
        Schema::table('ticket', function (Blueprint $table) {
            $table->integer('ticket_user_id')->nullable()->change();
            $table->integer('ticket_concert_id')->nullable()->change();
            $table->string('ticket_code', 5)->nullable()->change();
            $table->boolean('ticket_redeem')->nullable()->default(0)->change();
            $table->string('ticket_file')->nullable()->after('ticket_redeem');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ticket', function (Blueprint $table) {
            $table->integer('ticket_user_id')->nullable(false)->change();
            $table->integer('ticket_concert_id')->nullable(false)->change();
            $table->string('ticket_code', 5)->nullable(false)->change();
            $table->boolean('ticket_redeem')->nullable(false)->default(0)->change();
            $table->dropColumn('ticket_file');
        });
    }
};
