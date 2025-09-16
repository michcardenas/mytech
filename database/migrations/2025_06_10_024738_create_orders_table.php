<?php
// database/migrations/xxxx_create_orders_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique(); // ORD-20250610-001
            
            // Usuario (nullable para guests)
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            
            // Información del cliente (para guests y backup)
            $table->string('customer_name');
            $table->string('customer_email');
            $table->string('customer_phone');
            $table->text('customer_address');
            
            // Ubicación de envío
            $table->foreignId('country_id')->constrained();
            $table->foreignId('city_id')->nullable()->constrained();
            
            // Costos
            $table->decimal('subtotal', 10, 2);
            $table->decimal('tax_amount', 10, 2)->default(0);
            $table->decimal('shipping_amount', 10, 2)->default(0);
            $table->decimal('total_amount', 10, 2);
            
            // Estado del pedido
            $table->enum('status', ['pending', 'confirmed', 'processing', 'shipped', 'delivered', 'cancelled'])->default('pending');
            $table->enum('payment_status', ['pending', 'paid', 'failed', 'refunded'])->default('pending');
            
            // Información adicional
            $table->text('notes')->nullable();
            $table->string('payment_method')->nullable(); // paypal, stripe, etc.
            $table->string('payment_transaction_id')->nullable();
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('orders');
    }
};