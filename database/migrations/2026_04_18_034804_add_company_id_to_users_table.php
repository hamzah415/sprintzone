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
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('company_id')
              ->nullable() // Opsional: biarkan null jika ada user (seperti Admin) tanpa perusahaan
              ->after('id') // Menempatkan kolom setelah kolom 'id'
              ->constrained() // Mengasumsikan nama tabelnya adalah 'companies'
              ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['company_id']);
        $table->dropColumn('company_id');
        });
    }
};
