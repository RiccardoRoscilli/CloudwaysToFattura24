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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('application_id');  // Relazione molti a uno con Applications
            $table->decimal('amount', 10, 2);  // Totale dell'ordine
            $table->date('start_date');  // Data di inizio del trimestre
            $table->date('end_date');  // Data di fine del trimestre
            $table->timestamps();

            // Definisci la relazione con la tabella applications
            $table->foreign('application_id')->references('id')->on('applications')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
