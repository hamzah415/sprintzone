<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('cart_items', function (Blueprint $table) {
            $table->foreignId('variant_id')->nullable()->constrained('product_variants')->onDelete('set null')->after('product_id');
        });
    }

    public function down()
    {
        Schema::table('cart_items', function (Blueprint $table) {
            $table->dropForeign(['variant_id']);
            $table->dropColumn('variant_id');
        });
    }
};
