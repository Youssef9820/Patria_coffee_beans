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
        Schema::create('production_orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique(); // رقم الأمر (مثلا: ROAST-101)
            $table->string('type'); // نوع العملية (roasting تحميص / blending توليف)
            $table->string('status')->default('draft'); // الحالة (مسودة - شغال - تم)
            $table->date('production_date'); // تاريخ التشغيل
            $table->text('notes')->nullable(); // ملاحظات
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('production_orders');
    }
};
