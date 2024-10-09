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
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->string('label');
            $table->string('application');
            $table->string('app_version');
            $table->string('app_fqdn');
            $table->string('sys_user');
            $table->string('cname')->nullable();
            $table->foreignId('server_id')->constrained()->onDelete('cascade'); // Relazione con il server
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};
