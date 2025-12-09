<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GreenCoffeeType extends Model
{
    use HasFactory;

    // Allow 'name' to be saved
    protected $fillable = ['name'];

    // Relationship: One Type has many Batches
    public function batches()
    {
        return $this->hasMany(GreenCoffeeBatch::class);
    }
}