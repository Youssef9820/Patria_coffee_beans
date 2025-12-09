<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductionOrderResource\Pages;
use App\Filament\Resources\ProductionOrderResource\RelationManagers;
use App\Models\ProductionOrder;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductionOrderResource extends Resource
{
    protected static ?string $model = ProductionOrder::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // --- بيانات الأمر الأساسية ---
                Forms\Components\Section::make('تفاصيل الأمر')
                    ->schema([
                        Forms\Components\TextInput::make('order_number')
                            ->label('رقم التشغيلة')
                            ->default('R-' . rand(1000, 9999)) // رقم عشوائي مؤقت
                            ->required(),
                        
                        Forms\Components\Select::make('type')
                            ->label('نوع العملية')
                            ->options([
                                'roasting' => 'تحميص',
                                'blending' => 'توليف / خلط',
                            ])
                            ->required(),

                        Forms\Components\DatePicker::make('production_date')
                            ->label('تاريخ التشغيل')
                            ->default(now())
                            ->required(),
                            
                        Forms\Components\Select::make('status')
                            ->label('الحالة')
                            ->options([
                                'draft' => 'مسودة (تجهيز)',
                                'processing' => 'جاري التشغيل',
                                'completed' => 'تم الانتهاء',
                            ])
                            ->default('draft')
                            ->required(),
                    ])->columns(2),

                // --- المدخلات (نسحب منين؟) ---
                Forms\Components\Section::make('المكونات (المسحوبات)')
                    ->schema([
                        Forms\Components\Repeater::make('inputs') // ريبيتر عشان تضيف كذا شيكارة
                            ->relationship('inputs')
                            ->schema([
                                Forms\Components\Select::make('batch_id')
                                    ->label('اختر التشغيلة (الشيكارة)')
                                    ->options(function () {
                                        // هنا بنجيب الباتشات اللي فيها رصيد بس
                                        return \App\Models\Batch::where('current_weight', '>', 0)
                                            ->get()
                                            ->mapWithKeys(function ($batch) {
                                                // بنكتب الكود واسم الصنف عشان نسهل الاختيار
                                                return [$batch->id => $batch->batch_code . ' - ' . $batch->item->name . ' (' . $batch->current_weight . ' كجم)'];
                                            });
                                    })
                                    ->searchable()
                                    ->required()
                                    ->columnSpan(2),

                                Forms\Components\TextInput::make('weight_used')
                                    ->label('الوزن المسحوب (كجم)')
                                    ->numeric()
                                    ->required(),
                            ])
                            ->columns(3)
                            ->label('إضافة مكون')
                            ->addActionLabel('إضافة شيكارة أخرى'),
                    ]),

                // --- المخرجات (طلعنا ايه؟) ---
                Forms\Components\Section::make('الإنتاج (الناتج النهائي)')
                    ->schema([
                        Forms\Components\Repeater::make('outputs')
                            ->relationship('outputs')
                            ->schema([
                                Forms\Components\Select::make('item_id')
                                    ->label('الصنف الناتج')
                                    ->relationship('item', 'name') // هات كل الأصناف
                                    ->searchable()
                                    ->required(),

                                Forms\Components\TextInput::make('weight_produced')
                                    ->label('الوزن الناتج الصافي')
                                    ->numeric()
                                    ->required(),

                                Forms\Components\TextInput::make('cost_per_kg')
                                    ->label('تكلفة الكيلو الجديد')
                                    ->numeric()
                                    ->prefix('EGP')
                                    ->required(),
                            ])
                            ->columns(3)
                            ->label('إضافة ناتج')
                            ->addActionLabel('إضافة منتج'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order_number')->label('رقم الأمر')->searchable(),
                
                Tables\Columns\TextColumn::make('type')
                    ->label('العملية')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'roasting' => 'warning',
                        'blending' => 'info',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'roasting' => 'تحميص',
                        'blending' => 'توليف',
                        default => $state,
                    }),

                Tables\Columns\TextColumn::make('production_date')->label('التاريخ')->date(),
                
                Tables\Columns\TextColumn::make('status')
                    ->label('الحالة')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'draft' => 'gray',
                        'processing' => 'warning',
                        'completed' => 'success',
                    }),
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
            'index' => Pages\ListProductionOrders::route('/'),
            'create' => Pages\CreateProductionOrder::route('/create'),
            'edit' => Pages\EditProductionOrder::route('/{record}/edit'),
        ];
    }
}
