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
        Schema::table('borrower_profiles', function (Blueprint $table) {
            $table->decimal('tabungan_pokok', 12, 2)->default(0)->after('jenis_simpanan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('borrower_profiles', function (Blueprint $table) {
            $table->dropColumn('tabungan_pokok');
        });
    }
};
