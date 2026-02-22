<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id('product_id'); // unsigned BIGINT primary key
            $table->string('name');
            $table->text('description');
            $table->decimal('cost_price', 10, 2);
            $table->decimal('sell_price', 10, 2);

            // Foreign key to categories.category_id
            $table->unsignedBigInteger('category_id')->nullable();
            $table->foreign('category_id')
                  ->references('category_id')
                  ->on('categories')
                  ->onDelete('set null');

            $table->string('img_path')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
