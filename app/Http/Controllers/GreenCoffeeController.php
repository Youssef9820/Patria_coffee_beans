<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GreenCoffeeType;
use App\Models\GreenCoffeeBatch;
use App\Models\GreenCoffeePayment;
use Carbon\Carbon; // <--- المكان الصحيح لاستدعاء المكتبة (فوق الكلاس)

class GreenCoffeeController extends Controller
{
    // 1. MAIN PAGE (With Financial Calculations)
public function index(Request $request)
    {
        // 1. تحديد الفترة الزمنية (فلتر الشهر والسنة)
        $month = $request->input('month', date('m'));
        $year = $request->input('year', date('Y'));
        
        // تواريخ البداية والنهاية للفلتر
        $startDate = \Carbon\Carbon::createFromDate($year, $month, 1)->startOfDay();
        $endDate = \Carbon\Carbon::createFromDate($year, $month, 1)->endOfMonth()->endOfDay();

        // 2. جلب الأنواع
        $types = GreenCoffeeType::all();

        // 3. حسابات خاصة بـ "جدول الجرد" (Period Stats) - مرتبطة بالشهر المحدد
        $globalBeginningWeight = 0;
        $globalBeginningValue = 0;
        $globalAddedWeight = 0;
        $globalAddedValue = 0;

        foreach ($types as $type) {
            // أ. رصيد أول المدة (ما قبل هذا الشهر)
            $historyBatches = $type->batches()
                ->where('batch_date', '<', $startDate)
                ->get();
                
            $type->beginning_weight = $historyBatches->sum('weight_kg');
            $type->beginning_cost = $historyBatches->sum('total_cost');

            // ب. حركات الشهر الحالي (للعرض في الجدول)
            $type->current_batches = $type->batches()
                ->whereBetween('batch_date', [$startDate, $endDate])
                ->get();

            $type->added_weight = $type->current_batches->sum('weight_kg');
            $type->added_cost = $type->current_batches->sum('total_cost');

            // ج. رصيد آخر المدة
            $type->ending_weight = $type->beginning_weight + $type->added_weight;
            $type->ending_cost = $type->beginning_cost + $type->added_cost;

            // د. تجميع الإجماليات للداشبورد العلوي
            $globalBeginningWeight += $type->beginning_weight;
            $globalBeginningValue += $type->beginning_cost;
            $globalAddedWeight += $type->added_weight;
            $globalAddedValue += $type->added_cost;
        }

        $globalEndingWeight = $globalBeginningWeight + $globalAddedWeight;
        $globalEndingValue = $globalBeginningValue + $globalAddedValue;

        // 4. حسابات "التقرير المالي" (Financial Report) - دي شاملة كل الفترات
        // -----------------------------------------------------------
        // (ده الجزء اللي كان ناقص وسبب المشكلة)
        $allBatchesForFinance = GreenCoffeeBatch::with('payments')->get();
        
        $grandTotalCost = $allBatchesForFinance->sum('total_cost'); // إجمالي قيمة البضاعة تاريخياً
        $grandTotalPaid = $allBatchesForFinance->sum('paid_amount'); // إجمالي المدفوعات تاريخياً
        $grandTotalDebt = $grandTotalCost - $grandTotalPaid;         // إجمالي الديون الحالية

        // سجل المدفوعات وتصفية الديون
        $allPayments = GreenCoffeePayment::with('batch.type')->orderBy('payment_date', 'desc')->get();
        
        $debtBatches = $allBatchesForFinance->filter(function ($batch) {
            return $batch->remaining_amount > 0;
        });

        // إرسال كل البيانات للصفحة
        return view('green-coffee.index', compact(
            'types',
            'month', 'year', // الفلتر
            'globalBeginningWeight', 'globalBeginningValue', // أول المدة
            'globalEndingWeight', 'globalEndingValue',       // آخر المدة
            'grandTotalCost', 'grandTotalPaid', 'grandTotalDebt', // التقرير المالي (تم الإصلاح)
            'allPayments', 'debtBatches'
        ));
    }

    // 2. STORE NEW TYPE
    public function storeType(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);
        GreenCoffeeType::create(['name' => $request->name]);
        return redirect()->back()->with('success', 'Type added successfully');
    }

    // 3. STORE NEW INVENTORY BATCH
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