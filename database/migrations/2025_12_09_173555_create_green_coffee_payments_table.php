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
    Schema::create('green_coffee_payments', function (Blueprint $table) {
        $table->id();
        $table->foreignId('green_coffee_batch_id')->constrained()->onDelete('cascade');
        $table->decimal('amount', 10, 2); // How much paid
        $table->date('payment_date');     // When
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('green_coffee_payments');
    }
};
