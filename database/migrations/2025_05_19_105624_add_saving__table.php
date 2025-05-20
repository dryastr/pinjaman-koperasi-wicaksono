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
        Schema::table('savings', function (Blueprint $table) {
            $table->decimal('wajib_amount', 12, 2)->nullable()->after('amount');

            $table->decimal('sukarela_amount', 12, 2)->nullable()->after('wajib_amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('savings', function (Blueprint $table) {
            $table->dropColumn('wajib_amount');
            $table->dropColumn('sukarela_amount');
        });
    }
};
