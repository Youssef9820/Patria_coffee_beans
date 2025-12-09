<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GreenCoffeeType;
use App\Models\GreenCoffeeBatch;
use App\Models\GreenCoffeePayment; // Don't forget this

class GreenCoffeeController extends Controller
{
    // 1. MAIN PAGE (With Financial Calculations)
    public function index()
    {
        // Get all types with batches and payments
        $types = GreenCoffeeType::with(['batches.payments'])->get();

        // Global Calculations for the Financial Report
        $grandTotalCost = 0;
        $grandTotalPaid = 0;
        $allPayments = []; 
        $debtBatches = []; 

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

        // Sort payments (Newest first)
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

    // 2. STORE NEW TYPE (This was missing!)
    public function storeType(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);
        GreenCoffeeType::create(['name' => $request->name]);
        return redirect()->back()->with('success', 'Type added successfully');
    }

    // 3. STORE NEW INVENTORY BATCH (This was also likely missing or incomplete)
    public function storeBatch(Request $request)
    {
        $request->validate([
            'green_coffee_type_id' => 'required|exists:green_coffee_types,id',
            'weight_kg' => 'required|numeric|min:0.1',
            'batch_date' => 'required|date',
            'batch_time' => 'required',
        ]);

        $weight = $request->weight_kg;
        $pricePerKg = $request->price_per_kg;
        $totalCost = $request->total_cost;

        // Logic: Calculate missing price
        if ($pricePerKg) {
            $totalCost = $weight * $pricePerKg;
        } elseif ($totalCost) {
            $pricePerKg = $totalCost / $weight;
        }

        // Create the Batch
        $batch = GreenCoffeeBatch::create([
            'green_coffee_type_id' => $request->green_coffee_type_id,
            'weight_kg' => $weight,
            'price_per_kg' => $pricePerKg,
            'total_cost' => $totalCost,
            'batch_date' => $request->batch_date,
            'batch_time' => $request->batch_time,
        ]);

        // Add Initial Payment (if provided)
        if ($request->filled('paid_amount') && $request->paid_amount > 0) {
            $batch->payments()->create([
                'amount' => $request->paid_amount,
                'payment_date' => $request->batch_date,
            ]);
        }

        return redirect()->back()->with('success', 'Inventory added successfully');
    }

    // 4. UPDATE TYPE
    public function updateType(Request $request, $id)
    {
        $request->validate(['name' => 'required|string|max:255']);
        $type = GreenCoffeeType::findOrFail($id);
        $type->update(['name' => $request->name]);
        return redirect()->back()->with('success', 'Type updated successfully');
    }

    // 5. DELETE TYPE
    public function destroyType($id)
    {
        $type = GreenCoffeeType::findOrFail($id);
        $type->delete(); 
        return redirect()->back()->with('success', 'Type deleted successfully');
    }

    // 6. UPDATE BATCH
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

        if ($pricePerKg) {
            $totalCost = $weight * $pricePerKg;
        } elseif ($totalCost) {
            $pricePerKg = $totalCost / $weight;
        } else {
             $pricePerKg = $batch->price_per_kg;
             $totalCost = $weight * $pricePerKg;
        }

        $batch->update([
            'weight_kg' => $weight,
            'price_per_kg' => $pricePerKg,
            'total_cost' => $totalCost,
            'batch_date' => $request->batch_date,
            'batch_time' => $request->batch_time,
        ]);

        return redirect()->back()->with('success', 'Inventory updated successfully');
    }

    // 7. DELETE BATCH
    public function destroyBatch($id)
    {
        GreenCoffeeBatch::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Inventory item deleted');
    }

    // 8. STORE PAYMENT (Settlement)
    public function storePayment(Request $request)
    {
        $request->validate([
            'batch_id' => 'required|exists:green_coffee_batches,id',
            'amount' => 'required|numeric|min:1',
            'payment_date' => 'required|date',
        ]);

        GreenCoffeePayment::create([
            'green_coffee_batch_id' => $request->batch_id,
            'amount' => $request->amount,
            'payment_date' => $request->payment_date,
        ]);

        return redirect()->back()->with('success', 'Payment recorded successfully');
    }
}