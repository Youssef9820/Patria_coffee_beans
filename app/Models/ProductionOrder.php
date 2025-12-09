<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductionOrder extends Model
{
    use HasFactory;
    protected $guarded = [];

    // علاقة المدخلات (المكونات)
    public function inputs()
    {
        return $this->hasMany(ProductionInput::class);
    }

    // علاقة المخرجات (المنتج النهائي)
    public function outputs()
    {
        return $this->hasMany(ProductionOutput::class);
    }
    protected static function booted()
    {
        static::updated(function ($order) {
            // الشرط: لو الحالة اتغيرت وبقت "مكتمل" (completed)
            if ($order->isDirty('status') && $order->status === 'completed') {
                
                // 1. خصم الكميات من شكاير البن الأخضر (Inputs)
                foreach ($order->inputs as $input) {
                    $batch = $input->batch;
                    if ($batch) {
                        // انقص الوزن الحالي
                        $batch->decrement('current_weight', $input->weight_used);
                    }
                }

                // 2. إنشاء شكاير جديدة للبن المحمص (Outputs)
                foreach ($order->outputs as $output) {
                    \App\Models\Batch::create([
                        'batch_code' => $order->order_number . '-' . $output->item->id, // بنعمل كود جديد أوتوماتيك
                        'item_id' => $output->item_id,
                        'warehouse_id' => 1, // هنفترض إنه نزل في المخزن الرئيسي (ممكن نغيره بعدين)
                        'initial_weight' => $output->weight_produced,
                        'current_weight' => $output->weight_produced, // الرصيد الجديد
                        'unit_cost' => $output->cost_per_kg, // التكلفة اللي حسبناها
                        'purchase_date' => $order->production_date,
                        'supplier_id' => null, // ده تصنيع داخلي ملوش مورد خارجي
                    ]);
                }
            }
        });
    }
}