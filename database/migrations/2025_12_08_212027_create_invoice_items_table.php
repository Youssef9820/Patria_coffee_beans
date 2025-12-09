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
        Schema::create('invoice_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained()->cascadeOnDelete();
            
            // بنبيع من باتش معين عشان نخصم منه
            $table->foreignId('batch_id')->constrained()->cascadeOnDelete();
            
            $table->decimal('quantity', 10, 2); // الكمية المباعة
            $table->decimal('unit_price', 10, 2); // سعر البيع (غير سعر التكلفة)
            $table->decimal('total_price', 10, 2); // الإجمالي (الكمية * السعر)
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_items');
    }
};
