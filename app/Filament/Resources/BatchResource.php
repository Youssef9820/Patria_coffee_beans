<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BatchResource\Pages;
use App\Filament\Resources\BatchResource\RelationManagers;
use App\Models\Batch;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BatchResource extends Resource
{


    protected static ?string $model = Batch::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('بيانات الشحنة')
                    ->schema([
                        // اختيار الصنف من الداتا بيز
                        Forms\Components\Select::make('item_id')
                            ->relationship('item', 'name')
                            ->label('الصنف')
                            ->searchable()
                            ->preload()
                            ->required(),

                        // اختيار المخزن
                        Forms\Components\Select::make('warehouse_id')
                            ->relationship('warehouse', 'name')
                            ->label('المخزن')
                            ->default(1) // افتراضيا المخزن الرئيسي
                            ->required(),

                        // اختيار المورد (بيجيب الأسماء من جدول الشركاء)
                        Forms\Components\Select::make('supplier_id')
                            ->relationship('supplier', 'name')
                            ->label('المورد')
                            ->searchable(),

                        // كود الشوال (ممكن تكتبه يدوي أو باركود)
                        Forms\Components\TextInput::make('batch_code')
                            ->label('كود التشغيلة / رقم الشوال')
                            ->required()
                            ->unique(ignoreRecord: true),
                    ])->columns(2),

                Forms\Components\Section::make('التكلفة والوزن')
                    ->schema([
                        // الوزن اللي اشتريناه
                        Forms\Components\TextInput::make('initial_weight')
                            ->label('الوزن عند الشراء (كجم)')
                            ->numeric()
                            ->required()
                            ->live(onBlur: true) // أول ما تخلص كتابة وتدوس بره
                            ->afterStateUpdated(fn (Forms\Set $set, $state) => $set('current_weight', $state)), // انسخ الرقم للوزن الحالي

                        // الوزن المتبقي (بيتكتب لوحده)
                        Forms\Components\TextInput::make('current_weight')
                            ->label('الوزن الحالي بالمخزن')
                            ->numeric()
                            ->required()
                            ->readOnly(), // ممنوع التعديل اليدوي هنا في البداية

                        // الفلوس
                        Forms\Components\TextInput::make('unit_cost')
                            ->label('تكلفة الكيلو الواحد')
                            ->numeric()
                            ->prefix('EGP')
                            ->required(),

                        Forms\Components\DatePicker::make('purchase_date')
                            ->label('تاريخ الشراء')
                            ->default(now())
                            ->required(),
                    ])->columns(2),
            ]);
    }

public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('batch_code')
                    ->label('كود الشحنة')
                    ->searchable()
                    ->sortable()
                    ->copyable(), // زرار نسخ للكود

                Tables\Columns\TextColumn::make('item.name')
                    ->label('الصنف')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('warehouse.name')
                    ->label('المكان')
                    ->sortable(),

                Tables\Columns\TextColumn::make('current_weight')
                    ->label('الوزن الحالي')
                    ->suffix(' كجم')
                    ->color(fn (string $state): string => $state < 50 ? 'danger' : 'success') // يحمر لو الكمية قليلة
                    ->sortable(),

                Tables\Columns\TextColumn::make('unit_cost')
                    ->label('تكلفة الكيلو')
                    ->money('EGP')
                    ->sortable(),
            ])
            ->filters([
                // فلتر عشان نجيب الأصناف اللي خلصت أو لسه فيها
                Tables\Filters\Filter::make('has_stock')
                    ->label('المتوفر فقط')
                    ->query(fn ($query) => $query->where('current_weight', '>', 0)),
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
            'index' => Pages\ListBatches::route('/'),
            'create' => Pages\CreateBatch::route('/create'),
            'edit' => Pages\EditBatch::route('/{record}/edit'),
        ];
    }
}
