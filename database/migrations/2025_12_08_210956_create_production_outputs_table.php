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
    Schema::create('production_outputs', function (Blueprint $table) {
        $table->id();
        $table->foreignId('production_order_id')->constrained()->cascadeOnDelete();
        
        // الصنف اللي نتج (بن محمص / توليفة إسبريسو)
        $table->foreignId('item_id')->constrained()->cascadeOnDelete();
        
        $table->decimal('weight_produced', 10, 2); // الوزن الناتج (85 كيلو)
        $table->decimal('loss_percentage', 5, 2)->default(0); // نسبة الهالك (بتتحسب لوحدها)
        $table->decimal('cost_per_kg', 10, 2); // تكلفة الكيلو الجديد بعد الهالك
        
        // عشان السيستم يعمل باتش جديد للبن المحمص ده
        $table->string('new_batch_code')->nullable(); 
        
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('production_outputs');
    }
};
