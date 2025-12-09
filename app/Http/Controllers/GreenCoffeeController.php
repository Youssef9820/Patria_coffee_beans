<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GreenCoffeeType;
use App\Models\GreenCoffeeBatch;

class GreenCoffeeController extends Controller
{
    // 1. Show the main page
public function index()
{
    // 1. Get all types with batches and payments
    $types = GreenCoffeeType::with(['batches.payments'])->get();

    // 2. Global Calculations
    $grandTotalCost = 0;
    $grandTotalPaid = 0;
    $allPayments = []; // To store history
    $debtBatches = []; // To store batches with debt

    foreach ($types as $type) {
        foreach ($type->batches as $batch) {
            $grandTotalCost += $batch->total_cost;
            $paid = $batch->paid_amount;
            $grandTotalPaid += $paid;

            // Collect Unpaid Batches
            if ($batch->remaining_amount > 0) {
                $debtBatches[] = $batch;
            }

            // Collect All Payments for History
            foreach ($batch->payments as $payment) {
                $allPayments[] = [
                    'date' => $payment->payment_date,
                    'amount' => $payment->amount,
                    'type_name' => $type->name,
                    'batch_date' => $batch->batch_date,
                    'id' => $payment->id
                ];
            }
        }
    }

    $grandTotalDebt = $grandTotalCost - $grandTotalPaid;

    // Sort payments by date (Newest first)
    usort($allPayments, function ($a, $b) {
        return strtotime($b['date']) - strtotime($a['date']);
    });

    return view('green-coffee.index', compact(
        'types', 
        'grandTotalCost', 
        'grandTotalPaid', 
        'grandTotalDebt', 
        'allPayments', 
        'debtBatches'
    ));
}

    // 3. Store new Inventory (The Bag)
public function storeBatch(Request $request)
{
    $request->validate([
        'green_coffee_type_id' => 'required|exists:green_coffee_types,id',
        'weight_kg' => 'required|numeric|min:0.1',
        'batch_date' => 'required|date',
        'batch_time' => 'required',
        // 'paid_amount' is optional
    ]);

    // ... (Keep your existing Price Calculation Logic here) ...
    // Calculate $totalCost based on inputs (same as before)
    $weight = $request->weight_kg;
    $pricePerKg = $request->price_per_kg;
    $totalCost = $request->total_cost;
    
    if ($pricePerKg) {
        $totalCost = $weight * $pricePerKg;
    } elseif ($totalCost) {
        $pricePerKg = $totalCost / $weight;
    }

    // 1. Create the Batch
    $batch = GreenCoffeeBatch::create([
        'green_coffee_type_id' => $request->green_coffee_type_id,
        'weight_kg' => $weight,
        'price_per_kg' => $pricePerKg,
        'total_cost' => $totalCost,
        'batch_date' => $request->batch_date,
        'batch_time' => $request->batch_time,
    ]);

    // 2. Add Initial Payment (if any)
    if ($request->filled('paid_amount') && $request->paid_amount > 0) {
        $batch->payments()->create([
            'amount' => $request->paid_amount,
            'payment_date' => $request->batch_date, // Assumed same day
        ]);
    }

    return redirect()->back()->with('success', 'Inventory added successfully');
}
        // 4. Update Type Name
    public function updateType(Request $request, $id)
    {
        $request->validate(['name' => 'required|string|max:255']);
        $type = GreenCoffeeType::findOrFail($id);
        $type->update(['name' => $request->name]);
        return redirect()->back()->with('success', 'Type updated successfully');
    }

    // 5. Delete Type (and all its batches)
    public function destroyType($id)
    {
        $type = GreenCoffeeType::findOrFail($id);
        $type->delete(); // Cascades to batches automatically if DB is set up, otherwise:
        return redirect()->back()->with('success', 'Type deleted successfully');
    }

    // 6. Update Batch (Inventory)
    public function updateBatch(Request $request, $id)
    {
        $batch = GreenCoffeeBatch::findOrFail($id);
        
        $request->validate([
            'weight_kg' => 'required|numeric|min:0.1',
            'batch_date' => 'required|date',
            'batch_time' => 'required',
        ]);

        $weight = $request->weight_kg;
        $pricePerKg = $request->price_per_kg;
        $totalCost = $request->total_cost;

        // Recalculate Logic
        if ($pricePerKg) {
            $totalCost = $weight * $pricePerKg;
        } elseif ($totalCost) {
            $pricePerKg = $totalCost / $weight;
        } else {
             // Fallback if user cleared both: keep old price per kg
             $pricePerKg = $batch->price_per_kg;
             $totalCost = $weight * $pricePerKg;
        }

        $batch->update([
            'weight_kg' => $weight,
            'price_per_kg' => $pricePerKg,
            'total_cost' => $totalCost,
            'batch_date' => $request->batch_date,
            'batch_time' => $request->batch_time,
            // We usually don't change the Type of a batch, but you could add it if needed
        ]);

        return redirect()->back()->with('success', 'Inventory updated successfully');
    }

    // 7. Delete Batch
    public function destroyBatch($id)
    {
        GreenCoffeeBatch::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Inventory item deleted');
    }
    public function storePayment(Request $request)
{
    $request->validate([
        'batch_id' => 'required|exists:green_coffee_batches,id',
        'amount' => 'required|numeric|min:1',
        'payment_date' => 'required|date',
    ]);

    \App\Models\GreenCoffeePayment::create([
        'green_coffee_batch_id' => $request->batch_id,
        'amount' => $request->amount,
        'payment_date' => $request->payment_date,
    ]);

    return redirect()->back()->with('success', 'Payment recorded successfully');
}
}