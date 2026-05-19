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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique(); // Untuk URL ramah SEO (misal: pt-maju-jaya)
            $table->string('registration_number')->nullable()->comment('Nomor Izin/NIB');
            $table->string('tax_id')->nullable()->comment('NPWP');

            // Kontak & Media
            $table->string('email')->unique();
            $table->string('phone', 20)->nullable();
            $table->string('website')->nullable();
            $table->string('logo')->nullable(); // Path ke file gambar

            // Alamat Lengkap
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('province')->nullable();
            $table->string('postal_code', 10)->nullable();
            $table->string('country')->default('Indonesia');

            // Deskripsi & Pengaturan
            $table->text('description')->nullable();
            $table->string('industry')->nullable(); // Bidang usaha (misal: Teknologi, Konstruksi)
            $table->boolean('is_active')->default(true); // Status aktif/non-aktif

            // Audit Trail
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
