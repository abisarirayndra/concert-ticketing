<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement('DROP VIEW IF EXISTS v_ticket');

        DB::statement("
            CREATE VIEW v_ticket AS
            SELECT 
                t.ticket_id,
                t.ticket_user_id,
                t.ticket_concert_id,
                t.ticket_code,
                t.ticket_redeem,
                t.ticket_file,

                u.user_name AS user_name,
                u.user_name_last AS user_name_last,
                u.user_email AS user_email,

                c.*

            FROM ticket t
            JOIN user u ON t.ticket_user_id = u.user_id
            JOIN v_concert c ON t.ticket_concert_id = c.concert_id
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('v_ticket');
    }
};
