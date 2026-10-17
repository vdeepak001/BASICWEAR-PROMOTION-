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
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id'); // INT UNSIGNED AUTO_INCREMENT PRIMARY KEY
            $table->string('product_code', 50)->unique(); // e.g. '817', '856', 'LL817', 'CAP-01'
            $table->string('name', 200);
            $table->string('slug', 255)->unique();
            $table->string('short_desc', 500)->nullable();
            $table->mediumText('description')->nullable();
            $table->string('size', 150)->nullable();
            $table->string('material', 150)->nullable();
            $table->string('print_method', 150)->nullable();
            $table->unsignedInteger('moq')->default(1);
            $table->string('packing_info', 255)->nullable();
            $table->string('lead_time', 100)->nullable();
            $table->string('price_includes', 255)->nullable();
            $table->string('template_pdf_path', 255)->nullable();
            $table->boolean('is_new')->default(false);
            $table->boolean('is_hot')->default(false);
            $table->boolean('is_eco')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['is_active', 'is_new', 'is_hot', 'is_eco'], 'idx_product_flags');
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
