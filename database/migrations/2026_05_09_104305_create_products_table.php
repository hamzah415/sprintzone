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
        Schema::create('your_table_name', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->text('description')->nullable();

            $table->enum('status', ['active', 'inactive'])->default('active');

            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();

            $table->tinyInteger('is_deleted')->default(0);
            $table->timestamp('deleted_date')->nullable();

            $table->unsignedBigInteger('company_id')->nullable();
            $table->unsignedBigInteger('brand_id')->nullable();
            $table->unsignedBigInteger('category_id')->nullable();

            $table->timestamps();

            // Optional Foreign Keys
            // $table->foreign('company_id')->references('id')->on('companies');
            // $table->foreign('brand_id')->references('id')->on('brands');
            // $table->foreign('category_id')->references('id')->on('categories');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
