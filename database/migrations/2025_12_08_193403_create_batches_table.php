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
    Schema::create('batches', function (Blueprint $table) {
        $table->id();
        $table->foreignId('item_id')->constrained()->cascadeOnDelete(); // ربط بالصنف
        $table->foreignId('warehouse_id')->default(1); // ربط بالمخزن (ممكن نشيل constrained لو لسه ما ضفناش مخازن)
        $table->foreignId('supplier_id')->nullable()->constrained('partners'); // ربط بالمورد
        
        $table->string('batch_code'); // رقم الشوال
        
        $table->decimal('initial_weight', 10, 2); // الوزن الأساسي
        $table->decimal('current_weight', 10, 2); // الوزن الحالي
        $table->decimal('unit_cost', 10, 2); // سعر الكيلو
        
        $table->date('purchase_date'); // تاريخ الشراء
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('batches');
    }
};
