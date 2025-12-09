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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique(); // رقم الفاتورة (INV-001)
            
            $table->foreignId('client_id')->constrained('partners')->cascadeOnDelete(); // العميل
            
            $table->date('invoice_date'); // تاريخ البيع
            $table->string('payment_status')->default('unpaid'); // حالة الدفع (مدفوع/آجل)
            $table->decimal('total_amount', 10, 2)->default(0); // الإجمالي
            $table->decimal('discount', 10, 2)->default(0); // خصم
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
