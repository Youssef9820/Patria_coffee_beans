<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GreenBeanResource\Pages;
use App\Models\GreenBean;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class GreenBeanResource extends Resource
{
    protected static ?string $model = GreenBean::class;

    protected static ?string $navigationIcon = 'heroicon-o-cube';
    protected static ?string $navigationLabel = 'مخزن الأخضر (الجديد)';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // خانة واحدة بس للاسم عشان نجرب المودال
                Forms\Components\TextInput::make('name')
                    ->label('اسم نوع البن')
                    ->required(),

                Forms\Components\TextInput::make('alert_limit')
                    ->label('حد التنبيه')
                    ->numeric()
                    ->default(50),
            ]);
    }

public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('الاسم')->weight('bold')->searchable(),
                
                Tables\Columns\TextColumn::make('total_weight')
                    ->label('الرصيد الحالي')
                    ->state(fn ($record) => $record->batches()->sum('current_weight') . ' كجم')
                    ->color('success'),

                Tables\Columns\TextColumn::make('total_cost')
                    ->label('قيمة المخزون')
                    ->state(fn ($record) => number_format($record->batches->sum(fn($b)=>$b->current_weight*$b->unit_cost)) . ' EGP'),
            ])
            ->filters([])
            ->headerActions([
                Tables\Actions\CreateAction::make()->label('تعريف صنف جديد'),
            ])
            ->actions([
                // زرار الشراء (جمب الصنف علطول)
                Tables\Actions\Action::make('purchase')
                    ->label('شراء')
                    ->icon('heroicon-o-plus-circle')
                    ->button()
                    ->color('warning')
                    ->form([
                        \Filament\Forms\Components\Select::make('supplier_id')
                            ->label('المورد')
                            ->options(\App\Models\Partner::where('type', 'supplier')->pluck('name', 'id'))
                            ->searchable()->required(),
                        \Filament\Forms\Components\TextInput::make('weight')->label('الوزن')->numeric()->required(),
                        \Filament\Forms\Components\TextInput::make('cost')->label('السعر')->numeric()->required(),
                        \Filament\Forms\Components\TextInput::make('batch_code')->label('رقم الشوال')->default(fn()=>'GB-'.rand(100,999))->required(),
                    ])
                    ->action(function ($record, array $data) {
                        // $record هو الصنف اللي دوسنا عليه (GreenBean)
                        \App\Models\Batch::create([
                            'green_bean_id' => $record->id, // الربط الجديد
                            'item_id' => 1, // *مؤقتاً* هنحط 1 عشان الجدول القديم ميزعلش (لحد ما نحدثه)
                            'warehouse_id' => 1,
                            'supplier_id' => $data['supplier_id'],
                            'batch_code' => $data['batch_code'],
                            'initial_weight' => $data['weight'],
                            'current_weight' => $data['weight'],
                            'unit_cost' => $data['cost'],
                            'purchase_date' => now(),
                        ]);
                    }),
            ]);
    }
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGreenBeans::route('/'),
        ];
    }
}