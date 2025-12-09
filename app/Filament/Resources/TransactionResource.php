<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransactionResource\Pages;
use App\Filament\Resources\TransactionResource\RelationManagers;
use App\Models\Transaction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

public static function form(Form $form): Form
{
    return $form
        ->schema([
            Forms\Components\Section::make('تفاصيل الحركة المالية')
                ->schema([
                    Forms\Components\Select::make('type')
                        ->label('نوع الحركة')
                        ->options([
                            'income' => 'قبض / إيراد (داخل)',
                            'expense' => 'صرف / مصروف (خارج)',
                        ])
                        ->required()
                        ->reactive(), // عشان يغير الاختيارات اللي تحته

                        Forms\Components\Select::make('category')
                            ->label('بند الصرف/القبض')
                            ->options([
                                'sales_collection' => 'تحصيل من عميل',
                                'supplier_payment' => 'سداد لمورد',
                                'general_expense' => 'مصاريف عمومية (كهرباء/غاز)',
                                'salaries' => 'رواتب وموظفين',
                                'capital' => 'رأس مال / إيداع بنكي',
                            ])
                            ->required()
                            ->live() 
                            ->afterStateUpdated(fn (Forms\Set $set) => $set('partner_id', null)), 

                    Forms\Components\TextInput::make('amount')
                        ->label('المبلغ')
                        ->numeric()
                        ->prefix('EGP')
                        ->required(),

                    Forms\Components\DatePicker::make('transaction_date')
                        ->label('التاريخ')
                        ->default(now())
                        ->required(),

                    // يظهر بس لو اخترنا تحصيل أو سداد
                    Forms\Components\Select::make('partner_id')
                        ->label('الطرف الثاني (العميل/المورد)')
                        ->relationship('partner', 'name')
                        ->searchable()
                        ->visible(fn (Forms\Get $get) => in_array($get('category'), ['sales_collection', 'supplier_payment'])),

                    Forms\Components\Textarea::make('description')
                        ->label('ملاحظات / وصف')
                        ->columnSpanFull(),
                ])->columns(2),
        ]);
}

public static function table(Table $table): Table
{
    return $table
        ->columns([
            Tables\Columns\TextColumn::make('transaction_date')->label('التاريخ')->date()->sortable(),
            
            Tables\Columns\TextColumn::make('type')
                ->label('النوع')
                ->badge()
                ->color(fn (string $state): string => match ($state) {
                    'income' => 'success',
                    'expense' => 'danger',
                })
                ->formatStateUsing(fn (string $state): string => match ($state) {
                    'income' => 'إيراد',
                    'expense' => 'مصروف',
                }),

            Tables\Columns\TextColumn::make('category')->label('البند'),
            
            Tables\Columns\TextColumn::make('amount')
                ->label('المبلغ')
                ->money('EGP')
                ->weight('bold'),

            Tables\Columns\TextColumn::make('partner.name')->label('العميل/المورد'),
        ])
        ->defaultSort('transaction_date', 'desc');
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
            'index' => Pages\ListTransactions::route('/'),
            'create' => Pages\CreateTransaction::route('/create'),
            'edit' => Pages\EditTransaction::route('/{record}/edit'),
        ];
    }
}
