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
        Schema::table('customers', function (Blueprint $table) {
            if (!Schema::hasColumn('customers', 'mobile')) {
                $table->string('mobile')->nullable();
            }

            if (!Schema::hasColumn('customers', 'fax')) {
                $table->string('fax')->nullable();
            }

            if (!Schema::hasColumn('customers', 'iban')) {
                $table->string('iban')->nullable();
            }

            if (!Schema::hasColumn('customers', 'website')) {
                $table->string('website')->nullable();
            }

            if (!Schema::hasColumn('customers', 'note')) {
                $table->text('note')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            //
        });
    }
};
