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
        // Aggiorna tutti gli ordini con status 'sentF24' a 'complete'
        DB::table('orders')
            ->where('status', 'sentF24')
            ->update(['status' => 'complete']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Ripristina gli ordini da 'complete' a 'sentF24' (solo quelli che erano sentF24)
        // Nota: questo è un rollback approssimativo, non possiamo sapere con certezza quali erano sentF24
        DB::table('orders')
            ->where('status', 'complete')
            ->update(['status' => 'sentF24']);
    }
};
