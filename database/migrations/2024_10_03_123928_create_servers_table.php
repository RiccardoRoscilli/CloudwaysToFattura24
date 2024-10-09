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
        Schema::create('servers', function (Blueprint $table) {
            $table->id();
            $table->string('label');
            $table->string('status');
            $table->string('tenant_id');
            $table->string('backup_frequency');
            $table->string('backup_retention');
            $table->boolean('local_backups');
            $table->string('backup_time');
            $table->boolean('is_terminated');
            $table->string('platform');
            $table->string('cloud');
            $table->string('region');
            $table->string('instance_type');
            $table->string('server_fqdn');
            $table->string('public_ip');
            $table->string('volume_size');
            $table->string('master_user');
            $table->string('master_password');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('servers');
    }
};
