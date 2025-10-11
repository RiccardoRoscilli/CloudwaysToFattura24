<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('mailboxes', function (Blueprint $table) {
            // Se esiste un indice/unique su email, rimuovilo prima
            // $table->dropUnique(['email']); // <-- decommenta se avevi unique
        });

        Schema::table('mailboxes', function (Blueprint $table) {
            $table->renameColumn('email', 'mailbox_email');
        });

        // Riapplica eventuale unique/index con il nuovo nome
        Schema::table('mailboxes', function (Blueprint $table) {
            // $table->unique('mailbox_email'); // <-- decommenta se serve
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mailboxes', function (Blueprint $table) {
            //
        });
    }
};
