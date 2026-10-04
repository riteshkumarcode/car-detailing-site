<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VehicleResource\Pages;
use App\Models\Vehicle;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class VehicleResource extends Resource
{
    protected static ?string $model = Vehicle::class;

    protected static ?string $navigationIcon = 'heroicon-o-truck';
    protected static ?string $navigationGroup = 'Studio Operations';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Vehicle Profile')
                    ->schema([
                        Forms\Components\Select::make('customer_id')
                            ->relationship('customer', 'name')
                            ->searchable()
                            ->required()
                            ->label('Owner / Customer'),
                        Forms\Components\TextInput::make('registration_number')
                            ->required()
                            ->label('Registration Number')
                            ->placeholder('e.g. JK02ZZ9999'),
                        Forms\Components\TextInput::make('make')
                            ->required()
                            ->placeholder('e.g. BMW, Mahindra, Hyundai'),
                        Forms\Components\TextInput::make('model')
                            ->required()
                            ->placeholder('e.g. M340i, Thar, Creta'),
                        Forms\Components\TextInput::make('variant')
                            ->placeholder('e.g. xDrive, AX7L, Turbo DCT'),
                        Forms\Components\Select::make('vehicle_type')
                            ->options([
                                'hatchback' => 'Hatchback',
                                'sedan'     => 'Sedan',
                                'suv'       => 'SUV / Crossover',
                                'luxury'    => 'Luxury / Exotic',
                            ])
                            ->required(),
                        Forms\Components\TextInput::make('colour')
                            ->placeholder('e.g. Tanzanite Blue, Stealth Black'),
                        Forms\Components\Textarea::make('notes')
                            ->columnSpanFull()
                            ->rows(3),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('registration_number')
                    ->label('Plate #')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->fontFamily('mono'),
                Tables\Columns\TextColumn::make('make')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('model')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('vehicle_type')
                    ->badge()
                    ->sortable(),
                Tables\Columns\TextColumn::make('customer.name')
                    ->label('Owner')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('customer.mobile')
                    ->label('Mobile')
                    ->searchable(),
                Tables\Columns\TextColumn::make('total_visits')
                    ->label('Visits')
                    ->badge()
                    ->color('success'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('vehicle_type')
                    ->options([
                        'hatchback' => 'Hatchback',
                        'sedan'     => 'Sedan',
                        'suv'       => 'SUV',
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
            'index' => Pages\ListVehicles::route('/'),
            'create' => Pages\CreateVehicle::route('/create'),
            'edit' => Pages\EditVehicle::route('/{record}/edit'),
        ];
    }
}
