<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GreenCoffeeBatch extends Model
{
    use HasFactory;

    // Allow these fields to be saved
    protected $fillable = [
        'green_coffee_type_id',
        'weight_kg',
        'price_per_kg',
        'total_cost',
        'batch_date',
        'batch_time',
    ];

    public function type()
    {
        return $this->belongsTo(GreenCoffeeType::class, 'green_coffee_type_id');
    }
    
    public function payments()
    {
        return $this->hasMany(GreenCoffeePayment::class);
    }

    // Helper to calculate paid amount
    public function getPaidAmountAttribute()
    {
        return $this->payments->sum('amount');
    }

    // Helper to calculate remaining debt
    public function getRemainingAmountAttribute()
    {
        return $this->total_cost - $this->paid_amount;
    }
}