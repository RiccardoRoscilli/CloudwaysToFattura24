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
        Schema::table('applications', function (Blueprint $table) {
            $table->string('app_user')->nullable();
            $table->string('app_password')->nullable();
            $table->string('sys_password')->nullable();
            $table->string('mysql_db_name')->nullable();
            $table->string('mysql_user')->nullable();
            $table->string('mysql_password')->nullable();
            $table->string('webroot')->nullable();
            $table->boolean('is_csr_available')->default(false);
            $table->boolean('lets_encrypt')->nullable();
            $table->string('app_version_id')->nullable();
            $table->string('cms_app_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
