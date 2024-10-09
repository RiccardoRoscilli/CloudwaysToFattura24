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
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->nullable();
            $table->string('service_code')->nullable(); // Identificativo del servizio
            $table->decimal('cloudways_price', 8, 2); // Prezzo acquistato da Cloudways
            $table->decimal('sale_price', 8, 2)->nullable(); // Prezzo rivenduto al cliente
            $table->string('unit_of_measure')->nullable(); // Unità di misura, es. "GB", "Ore"
            $table->integer('quantity')->default(1); // Quantità del servizio venduto
            $table->date('start_date')->nullable(); // Data inizio servizio
            $table->date('end_date')->nullable(); // Data fine servizio o rinnovo
            $table->string('service_type')->nullable(); // Tipo di servizio, es. hosting, supporto
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
