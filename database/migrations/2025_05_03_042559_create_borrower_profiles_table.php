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
        Schema::create('borrower_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            $table->string('alamat');
            $table->date('tanggal_lahir');
            $table->string('tempat_lahir');
            $table->string('pekerjaan');

            $table->string('foto_ktp');
            $table->string('foto_kk');
            $table->string('foto_diri');

            $table->enum('jenis_simpanan', ['pokok', 'wajib', 'sukarela']);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('borrower_profiles');
    }
};
