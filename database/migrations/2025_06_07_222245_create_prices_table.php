<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::create('prices', function (Blueprint $table) {
        $table->id();
        $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
        $table->decimal('price', 10, 2);
        $table->decimal('interest', 10, 2)->nullable();
        $table->decimal('shipping', 10, 2)->nullable(); // "envio"
        $table->foreignId('country_id')->constrained('countries')->onDelete('cascade');
        $table->foreignId('city_id')->constrained('cities')->onDelete('cascade');
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prices');
    }
};
