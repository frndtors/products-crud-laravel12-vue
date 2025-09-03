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
            $table->id();
            $table->string('name')->comment('Product name');
            $table->decimal('price', 10, 2)->comment('Product price with 2 decimal places');
            $table->integer('stock')->default(0)->comment('Product stock quantity');
            $table->text('description')->nullable()->comment('Product description');
            $table->timestamps();

            // Indexes for performance
            $table->index('name');
            $table->index('price');
        });
    }

    /**
     * Run the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
