<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InvoiceResource\Pages;
use App\Filament\Resources\InvoiceResource\RelationManagers;
use App\Models\Invoice;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class InvoiceResource extends Resource
{
    protected static ?string $model = Invoice::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // --- بيانات الفاتورة (الهيدر) ---
                Forms\Components\Section::make('بيانات الفاتورة')
                    ->schema([
                        Forms\Components\TextInput::make('invoice_number')
                            ->label('رقم الفاتورة')
                            ->default('INV-' . date('Ymd') . '-' . rand(10, 99)) // رقم تلقائي
                            ->required(),

                        Forms\Components\Select::make('client_id')
                            ->label('العميل')
                            ->relationship('client', 'name')
                            ->searchable()
                            ->preload()
                            ->createOptionForm([ // زرار سريع لإضافة عميل جديد
                                Forms\Components\TextInput::make('name')->required()->label('الاسم'),
                                Forms\Components\Select::make('type')->options(['client' => 'عميل', 'distributor' => 'تاجر'])->default('client')->required(),
                            ])
                            ->required(),

                        Forms\Components\DatePicker::make('invoice_date')
                            ->label('التاريخ')
                            ->default(now())
                            ->required(),

                        Forms\Components\Select::make('payment_status')
                            ->label('طريقة الدفع')
                            ->options([
                                'paid' => 'كاش (مدفوع)',
                                'unpaid' => 'آجل (على الحساب)',
                            ])
                            ->default('paid')
                            ->required(),
                    ])->columns(2),

                // --- بنود الفاتورة (الأصناف) ---
                Forms\Components\Section::make('الأصناف المباعة')
                    ->schema([
                        Forms\Components\Repeater::make('items')
                            ->relationship('items')
                            ->schema([
                                // اختيار الباتش (بيظهر الاسم + الوزن المتاح)
                                Forms\Components\Select::make('batch_id')
                                    ->label('الصنف')
                                    ->options(function () {
                                        return \App\Models\Batch::where('current_weight', '>', 0)
                                            ->get()
                                            ->mapWithKeys(function ($batch) {
                                                return [$batch->id => $batch->item->name . ' (' . $batch->current_weight . ' كجم متاح)'];
                                            });
                                    })
                                    ->searchable()
                                    ->required()
                                    ->columnSpan(2), // ياخد مساحة كبيرة

                                Forms\Components\TextInput::make('quantity')
                                    ->label('الكمية (كجم)')
                                    ->numeric()
                                    ->required()
                                    ->live(onBlur: true) // عشان يحسب الإجمالي لحظيا
                                    ->afterStateUpdated(function (Forms\Get $get, Forms\Set $set) {
                                        // معادلة: الكمية * السعر = الإجمالي
                                        $qty = $get('quantity');
                                        $price = $get('unit_price');
                                        $set('total_price', $qty * $price);
                                    }),

                                Forms\Components\TextInput::make('unit_price')
                                    ->label('سعر البيع')
                                    ->numeric()
                                    ->required()
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(function (Forms\Get $get, Forms\Set $set) {
                                        $qty = $get('quantity');
                                        $price = $get('unit_price');
                                        $set('total_price', $qty * $price);
                                    }),

                                Forms\Components\TextInput::make('total_price')
                                    ->label('الإجمالي')
                                    ->numeric()
                                    ->readOnly(), // للقراءة فقط
                            ])
                            ->columns(4)
                            ->addActionLabel('إضافة صنف آخر'),
                    ]),
            ]);
    }

public static function table(Table $table): Table
{
    return $table
        ->columns([
            Tables\Columns\TextColumn::make('invoice_number')->label('رقم الفاتورة')->searchable(),
            Tables\Columns\TextColumn::make('client.name')->label('العميل')->searchable(),
            Tables\Columns\TextColumn::make('total_amount')->label('إجمالي الفاتورة')->money('EGP'),
            Tables\Columns\TextColumn::make('payment_status')
                ->label('الدفع')
                ->badge()
                ->color(fn (string $state): string => match ($state) {
                    'paid' => 'success',
                    'unpaid' => 'danger',
                }),
            Tables\Columns\TextColumn::make('invoice_date')->label('التاريخ')->date(),
        ])
        ->filters([
            //
        ]);
}
    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInvoices::route('/'),
            'create' => Pages\CreateInvoice::route('/create'),
            'edit' => Pages\EditInvoice::route('/{record}/edit'),
        ];
    }
}
