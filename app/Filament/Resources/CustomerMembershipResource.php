<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomerMembershipResource\Pages;
use App\Models\CustomerMembership;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CustomerMembershipResource extends Resource
{
    protected static ?string $model = CustomerMembership::class;

    protected static ?string $navigationIcon = 'heroicon-o-identification';
    protected static ?string $navigationGroup = 'Drive Club Memberships';
    protected static ?string $navigationLabel = 'Active Memberships';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Membership Record')
                    ->schema([
                        Forms\Components\Select::make('customer_id')
                            ->relationship('customer', 'name')
                            ->required()
                            ->disabled(),
                        Forms\Components\Select::make('vehicle_id')
                            ->relationship('vehicle', 'registration_number')
                            ->required()
                            ->disabled(),
                        Forms\Components\TextInput::make('plan_name')
                            ->required(),
                        Forms\Components\TextInput::make('price_paid')
                            ->numeric()
                            ->prefix('₹'),
                        Forms\Components\DateTimePicker::make('starts_at')
                            ->required(),
                        Forms\Components\DateTimePicker::make('expires_at')
                            ->required(),
                        Forms\Components\Select::make('status')
                            ->options([
                                'active'    => 'Active',
                                'expired'   => 'Expired',
                                'cancelled' => 'Cancelled',
                            ])
                            ->required(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('customer.name')
                    ->label('Member Name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('vehicle.registration_number')
                    ->label('Vehicle Plate')
                    ->searchable()
                    ->fontFamily('mono'),
                Tables\Columns\TextColumn::make('plan_name')
                    ->badge()
                    ->color('warning')
                    ->searchable(),
                Tables\Columns\TextColumn::make('price_paid')
                    ->money('INR'),
                Tables\Columns\TextColumn::make('expires_at')
                    ->date('d M Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'active' => 'success',
                        'expired' => 'danger',
                        'cancelled' => 'gray',
                        default => 'info',
                    }),
            ])
            ->defaultSort('created_at', 'desc')
            ->actions([
                Tables\Actions\EditAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCustomerMemberships::route('/'),
        ];
    }
}
