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
    Schema::create('green_coffee_batches', function (Blueprint $table) {
        $table->id();
        // Link to the coffee type
        $table->foreignId('green_coffee_type_id')->constrained()->onDelete('cascade');
        
        $table->decimal('weight_kg', 10, 2);      // Weight of this specific bag
        $table->decimal('price_per_kg', 10, 2);   // Cost of 1 KG
        $table->decimal('total_cost', 10, 2);     // Cost of the whole bag
        
        $table->date('batch_date');               // Date (dd-mm-yyyy)
        $table->time('batch_time');               // Time (12h format stored as time)
        
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('green_coffee_batches');
    }
};
