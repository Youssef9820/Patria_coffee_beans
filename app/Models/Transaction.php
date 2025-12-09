<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function partner()
    {
        return $this->belongsTo(Partner::class);
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    protected static function booted()
    {
        static::created(function ($transaction) {
            if ($transaction->partner_id) {
                $partner = $transaction->partner;
                
                // لو قبضنا فلوس (Income) -> رصيد العميل ينقص (الدين بيقل)
                if ($transaction->type == 'income') {
                    $partner->decrement('balance', $transaction->amount);
                }
                
                // لو دفعنا فلوس لمورد (Expense) -> رصيدنا عنده ينقص (الدين بيقل)
                elseif ($transaction->type == 'expense') {
                    $partner->decrement('balance', $transaction->amount);
                }
            }
        });
    }
}