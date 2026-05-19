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
        	$table->boolean('has_2fa')->default(0); // 0 = Belum Setup, 1 = Sudah Setup
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
