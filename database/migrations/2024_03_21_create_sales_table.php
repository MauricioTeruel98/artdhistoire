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
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->decimal('amount', 10, 2);
            $table->string('currency', 3)->default('EUR'); // EUR o USD
            $table->string('payment_method'); // stripe o paypal
            $table->string('status')->default('completed');
            $table->string('payment_id')->nullable(); // ID de la transacción en Stripe/PayPal
            $table->string('description')->nullable();
            $table->timestamps();

            // Índices para mejorar el rendimiento de las consultas
            $table->index(['created_at']);
            $table->index(['payment_method']);
            $table->index(['currency']);
            $table->index(['status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};