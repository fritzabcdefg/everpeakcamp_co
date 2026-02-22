<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id('order_id'); // unsigned BIGINT primary key

            // Foreign key to users.id
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->onDelete('cascade');

            // Foreign key to customers.customer_id
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->foreign('customer_id')
                  ->references('customer_id')
                  ->on('customers')
                  ->onDelete('set null');

            $table->decimal('total_amount', 10, 2);
            $table->string('status')->default('pending');
            $table->timestamp('order_date')->useCurrent();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
