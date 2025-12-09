<?php

namespace App\Filament\Widgets;

use App\Models\Batch;
use App\Models\Invoice;
use App\Models\Partner;
use App\Models\Transaction;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    // الرقم ده بيحدد سرعة تحديث البيانات (كل 15 ثانية)
    protected static ?string $pollingInterval = '15s';

    protected function getStats(): array
    {
        return [
            // 1. كارت المبيعات (الشهر الحالي)
            Stat::make('مبيعات هذا الشهر', number_format(Invoice::whereMonth('invoice_date', now()->month)->sum('total_amount')) . ' EGP')
                ->description('إجمالي الفواتير')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chart([7, 2, 10, 3, 15, 4, 17]) // رسم بياني وهمي للديكور
                ->color('success'), // لون أخضر

            // 2. كارت المصاريف (الشهر الحالي)
            Stat::make('مصاريف هذا الشهر', number_format(Transaction::where('type', 'expense')->whereMonth('transaction_date', now()->month)->sum('amount')) . ' EGP')
                ->description('تشمل المشتريات والرواتب')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('danger'), // لون أحمر

            // 3. كارت فلوس لينا بره (الآجل)
            Stat::make('مديونيات العملاء', number_format(Partner::where('type', 'client')->sum('balance')) . ' EGP')
                ->description('فلوس مستحقة التحصيل')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('warning'), // لون أصفر

            // 4. كارت وزن المخزون (كله)
            Stat::make('رصيد المخازن الحالي', number_format(Batch::sum('current_weight')) . ' كجم')
                ->description('يشمل الأخضر والمحمص')
                ->descriptionIcon('heroicon-m-archive-box')
                ->color('info'), // لون أزرق
        ];
    }
}