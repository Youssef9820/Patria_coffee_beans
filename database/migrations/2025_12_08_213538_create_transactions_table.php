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
    Schema::create('transactions', function (Blueprint $table) {
        $table->id();
        
        $table->string('type'); // نوع الحركة: (income دخل / expense مصروف)
        $table->string('category'); // التصنيف: (مبيعات، كهرباء، رواتب، سداد مورد)
        
        $table->decimal('amount', 15, 2); // المبلغ
        
        // لو الحركة تخص عميل أو مورد (عشان نخصم من حسابه)
        $table->foreignId('partner_id')->nullable()->constrained()->cascadeOnDelete();
        
        // لو الحركة تخص فاتورة معينة
        $table->foreignId('invoice_id')->nullable()->constrained()->cascadeOnDelete();
        
        $table->date('transaction_date'); // التاريخ
        $table->text('description')->nullable(); // شرح (دفع جزء من الحساب)
        
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
