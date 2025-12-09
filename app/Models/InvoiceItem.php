<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    // --- الكود السحري هنا ---
    protected static function booted()
    {
        // 1. لما نبيع صنف جديد (Created)
        static::created(function ($item) {
            // أ: خصم الكمية من الباتش في المخزن
            if ($item->batch) {
                $item->batch->decrement('current_weight', $item->quantity);
            }
            
            // ب: إعادة حساب إجمالي الفاتورة
            $item->invoice->recalculateTotal();
        });

        // 2. لو عدلنا في صنف (Updated) - عشان نحدث السعر بس
        static::updated(function ($item) {
            $item->invoice->recalculateTotal();
        });

        // 3. لو مسحنا صنف من الفاتورة (Deleted)
        static::deleted(function ($item) {
            $item->invoice->recalculateTotal();
        });
    }
}
