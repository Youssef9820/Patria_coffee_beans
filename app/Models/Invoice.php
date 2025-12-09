<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;
    protected $guarded = [];

    // دي الدالة اللي كانت ناقصة وعملت المشكلة
    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    // علاقة العميل
    public function client()
    {
        return $this->belongsTo(Partner::class, 'client_id');
    }

    // دالة حساب الإجمالي
    public function recalculateTotal()
    {
        // بنجمع كل total_price من الأصناف ونحطه في total_amount
        $this->update([
            'total_amount' => $this->items->sum('total_price')
        ]);
    }
}