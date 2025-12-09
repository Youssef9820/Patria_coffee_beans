<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GreenCoffeePayment extends Model
{
    use HasFactory;

    protected $fillable = ['green_coffee_batch_id', 'amount', 'payment_date'];

    public function batch()
    {
        return $this->belongsTo(GreenCoffeeBatch::class, 'green_coffee_batch_id');
    }
}