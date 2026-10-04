<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HealthCheckResource\Pages;
use App\Models\HealthCheck;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class HealthCheckResource extends Resource
{
    protected static ?string $model = HealthCheck::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';
    protected static ?string $navigationGroup = 'Diagnostics & Quality';
    protected static ?string $navigationLabel = 'Health Checks';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Health Check Details')
                    ->schema([
                        Forms\Components\Select::make('vehicle_id')
                            ->relationship('vehicle', 'registration_number')
                            ->disabled()
                            ->label('Vehicle Plate'),
                        Forms\Components\Select::make('customer_id')
                            ->relationship('customer', 'name')
                            ->disabled()
                            ->label('Customer'),
                        Forms\Components\TextInput::make('overall_score')
                            ->numeric()
                            ->disabled()
                            ->label('Drive Health Score (/100)'),
                        Forms\Components\TextInput::make('protection_type')
                            ->disabled()
                            ->label('Active Protection Layer'),
                        Forms\Components\DateTimePicker::make('check_date')
                            ->disabled()
                            ->label('Date of Inspection'),
                        Forms\Components\Textarea::make('technician_notes')
                            ->disabled()
                            ->columnSpanFull(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('vehicle.registration_number')
                    ->label('Vehicle Plate')
                    ->searchable()
                    ->sortable()
                    ->fontFamily('mono')
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('customer.name')
                    ->label('Customer')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('overall_score')
                    ->label('Score')
                    ->badge()
                    ->color(fn (int $state): string => match (true) {
                        $state >= 80 => 'success',
                        $state >= 60 => 'warning',
                        default => 'danger',
                    })
                    ->suffix(' / 100')
                    ->sortable(),
                Tables\Columns\TextColumn::make('protection_type')
                    ->badge()
                    ->uppercase(),
                Tables\Columns\TextColumn::make('check_date')
                    ->label('Inspection Date')
                    ->dateTime('d M Y, h:i A')
                    ->sortable(),
            ])
            ->defaultSort('check_date', 'desc')
            ->actions([
                Tables\Actions\Action::make('view_report')
                    ->label('Report')
                    ->icon('heroicon-o-arrow-top-right-on-square')
                    ->url(fn (HealthCheck $record): string => route('health-report.show', $record->share_token))
                    ->openUrlInNewTab(),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListHealthChecks::route('/'),
        ];
    }
}
