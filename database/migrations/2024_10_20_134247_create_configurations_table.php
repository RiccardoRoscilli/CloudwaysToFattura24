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
        Schema::create('configurations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            // Campi per le configurazioni di posta
            $table->string('mail_mailer')->default('smtp');
            $table->string('mail_host')->nullable();
            $table->integer('mail_port')->nullable();
            $table->string('mail_username')->nullable();
            $table->string('mail_password')->nullable();
            $table->string('mail_encryption')->nullable();
            $table->string('mail_from_address')->nullable();
            $table->string('mail_from_name')->nullable();
            // Campi per Cloudways
            $table->string('cloudways_api_key')->nullable();
            $table->string('cloudways_email')->nullable();
            // Campi per Fattura24
            $table->string('fattura24_api_key')->nullable();
            // Campo di esempio per variabili di test
            $table->string('test_variable')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('configurations');
    }
};
