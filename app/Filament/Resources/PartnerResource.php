<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PartnerResource\Pages;
use App\Filament\Resources\PartnerResource\RelationManagers;
use App\Models\Partner;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PartnerResource extends Resource
{
    protected static ?string $model = Partner::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('الاسم')
                    ->required(),

                Forms\Components\Select::make('type')
                    ->label('التصنيف')
                    ->options([
                        'supplier' => 'مورد (بشتري منه)',
                        'client' => 'عميل (ببعله)',
                        'distributor' => 'تاجر / موزع',
                    ])
                    ->required(),

                Forms\Components\TextInput::make('phone')
                    ->label('رقم الهاتف')
                    ->tel(),

                Forms\Components\TextInput::make('balance')
                    ->label('الرصيد الافتتاحي')
                    ->numeric()
                    ->prefix('EGP')
                    ->default(0),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('الاسم')->searchable(),
                Tables\Columns\TextColumn::make('type')
                    ->label('التصنيف')
                    ->badge() // بيخلي شكلها شيك وملون
                    ->color(fn (string $state): string => match ($state) {
                        'supplier' => 'warning',
                        'client' => 'success',
                        'distributor' => 'info',
                    }),
                Tables\Columns\TextColumn::make('balance')->label('الرصيد')->money('EGP'),
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
            'index' => Pages\ListPartners::route('/'),
            'create' => Pages\CreatePartner::route('/create'),
            'edit' => Pages\EditPartner::route('/{record}/edit'),
        ];
    }
}
