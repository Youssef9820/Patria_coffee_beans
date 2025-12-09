<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ItemResource\Pages;
use App\Filament\Resources\ItemResource\RelationManagers;
use App\Models\Item;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ItemResource extends Resource
{
    protected static ?string $model = Item::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

        public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('اسم الصنف')
                    ->required(),
                
                Forms\Components\Select::make('type')
                    ->label('نوع الصنف')
                    ->options([
                        'green' => 'بن أخضر (خام)',
                        'roasted' => 'بن محمص',
                        'blend' => 'توليفة جاهزة',
                        'additive' => 'إضافات (حبهان/نكهات)',
                        'packaging' => 'مواد تعبئة',
                    ])
                    ->required(),

                Forms\Components\TextInput::make('alert_limit')
                    ->label('حد التنبيه (أقل كمية)')
                    ->numeric()
                    ->default(50),
            ]);
    }

    public static function table(Table $table): Table
        {
            return $table
                ->columns([
                    Tables\Columns\TextColumn::make('name')
                        ->label('الاسم')
                        ->searchable(),

                    Tables\Columns\TextColumn::make('type')
                        ->label('النوع')
                        ->badge()
                        ->formatStateUsing(fn (string $state): string => match ($state) {
                            'green' => 'بن أخضر',
                            'roasted' => 'بن محمص',
                            'blend' => 'توليفة',
                            'additive' => 'إضافات',
                            'packaging' => 'تعبئة',
                            default => $state,
                        })
                        ->color(fn (string $state): string => match ($state) {
                            'green' => 'success',
                            'roasted' => 'warning',
                            'blend' => 'info',
                            'additive' => 'danger',
                            'packaging' => 'gray',
                            default => 'gray',
                        })
                        ->sortable(),

                    Tables\Columns\TextColumn::make('alert_limit')
                        ->label('حد التنبيه (كجم)'),
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
            'index' => Pages\ListItems::route('/'),
            'create' => Pages\CreateItem::route('/create'),
            'edit' => Pages\EditItem::route('/{record}/edit'),
        ];
    }
}
