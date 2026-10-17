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
        Schema::create('quote_requests', function (Blueprint $table) {
            $table->increments('id');
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('quote_number', 50)->nullable()->unique();
            $table->string('name', 150);
            $table->string('company', 150)->nullable();
            $table->string('email', 190);
            $table->string('phone', 40)->nullable();
            $table->string('destination', 100)->nullable(); // Suburb/City/Postcode for shipping calculation
            $table->text('message')->nullable();
            $table->string('artwork_path', 255)->nullable();
            $table->date('needed_by')->nullable();
            $table->enum('status', ['new', 'contacted', 'quoted', 'won', 'lost'])->default('new');
            $table->timestamps();

            $table->index(['status', 'created_at'], 'idx_quote_status');
        });

        Schema::create('quote_items', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('quote_id');
            $table->unsignedInteger('product_id');
            $table->unsignedInteger('variant_id')->nullable();
            $table->unsignedInteger('quantity');
            $table->string('print_option', 150)->nullable();
            $table->string('notes', 500)->nullable();
            $table->timestamps();

            $table->foreign('quote_id')
                ->references('id')
                ->on('quote_requests')
                ->onDelete('cascade');

            $table->foreign('product_id')
                ->references('id')
                ->on('products');

            $table->foreign('variant_id')
                ->references('id')
                ->on('product_variants')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quote_items');
        Schema::dropIfExists('quote_requests');
    }
};
