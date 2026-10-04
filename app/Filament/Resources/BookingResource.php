<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BookingResource\Pages;
use App\Models\Booking;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class BookingResource extends Resource
{
    protected static ?string $model = Booking::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';
    protected static ?string $navigationGroup = 'Studio Operations';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Booking Details')
                    ->schema([
                        Forms\Components\Select::make('customer_id')
                            ->relationship('customer', 'name')
                            ->searchable()
                            ->label('Customer'),
                        Forms\Components\Select::make('vehicle_id')
                            ->relationship('vehicle', 'registration_number')
                            ->searchable()
                            ->label('Vehicle Plate'),
                        Forms\Components\Select::make('service_id')
                            ->relationship('service', 'name')
                            ->searchable()
                            ->required()
                            ->label('Service Treatment'),
                        Forms\Components\DatePicker::make('booking_date')
                            ->required()
                            ->default(now()),
                        Forms\Components\TimePicker::make('booking_time')
                            ->required()
                            ->default('10:00'),
                        Forms\Components\TextInput::make('price')
                            ->numeric()
                            ->prefix('₹')
                            ->required(),
                        Forms\Components\Select::make('status')
                            ->options([
                                'new'         => 'New',
                                'confirmed'   => 'Confirmed',
                                'arrived'     => 'Arrived',
                                'inspection'  => 'Under Inspection',
                                'in_service'  => 'In Service (Bay)',
                                'completed'   => 'Completed',
                                'cancelled'   => 'Cancelled',
                                'no_show'     => 'No Show',
                            ])
                            ->required()
                            ->default('confirmed'),
                        Forms\Components\Textarea::make('notes')
                            ->rows(2)
                            ->columnSpanFull(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('registration_number')
                    ->label('Vehicle Plate')
                    ->searchable()
                    ->sortable()
                    ->fontFamily('mono')
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('name')
                    ->label('Customer')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('service_name')
                    ->label('Service')
                    ->searchable(),
                Tables\Columns\TextColumn::make('booking_date')
                    ->date('d M Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('booking_time')
                    ->time('g:i A'),
                Tables\Columns\TextColumn::make('price')
                    ->money('INR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'new' => 'gray',
                        'confirmed' => 'info',
                        'arrived' => 'warning',
                        'inspection' => 'warning',
                        'in_service' => 'primary',
                        'completed' => 'success',
                        'cancelled' => 'danger',
                        'no_show' => 'danger',
                        default => 'gray',
                    }),
            ])
            ->defaultSort('booking_date', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'new' => 'New',
                        'confirmed' => 'Confirmed',
                        'arrived' => 'Arrived',
                        'in_service' => 'In Service',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBookings::route('/'),
            'create' => Pages\CreateBooking::route('/create'),
            'edit' => Pages\EditBooking::route('/{record}/edit'),
        ];
    }
}
