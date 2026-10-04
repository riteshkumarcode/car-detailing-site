<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ServiceResource\Pages;
use App\Models\Service;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class ServiceResource extends Resource
{
    protected static ?string $model = Service::class;

    protected static ?string $navigationIcon = 'heroicon-o-sparkles';
    protected static ?string $navigationGroup = 'Studio Services & Pricing';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Treatment Details')
                    ->schema([
                        Forms\Components\Select::make('service_category_id')
                            ->relationship('category', 'name')
                            ->required()
                            ->label('Service Category'),
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug($state))),
                        Forms\Components\TextInput::make('slug')
                            ->required()
                            ->unique(ignoreRecord: true),
                        Forms\Components\TextInput::make('duration_minutes')
                            ->numeric()
                            ->default(30)
                            ->required()
                            ->label('Duration (Minutes)'),
                        Forms\Components\Textarea::make('short_description')
                            ->required()
                            ->rows(2)
                            ->columnSpanFull(),
                    ])->columns(2),

                Forms\Components\Section::make('Pricing by Vehicle Type')
                    ->schema([
                        Forms\Components\TextInput::make('price_hatchback')
                            ->numeric()
                            ->prefix('₹')
                            ->required()
                            ->label('Hatchback Price'),
                        Forms\Components\TextInput::make('price_sedan')
                            ->numeric()
                            ->prefix('₹')
                            ->required()
                            ->label('Sedan Price'),
                        Forms\Components\TextInput::make('price_suv')
                            ->numeric()
                            ->prefix('₹')
                            ->required()
                            ->label('SUV Price'),
                        Forms\Components\TextInput::make('sort_order')
                            ->numeric()
                            ->default(1),
                        Forms\Components\Toggle::make('is_active')
                            ->label('Active on Studio Price List')
                            ->default(true),
                    ])->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('category.name')
                    ->badge()
                    ->color('info')
                    ->sortable(),
                Tables\Columns\TextColumn::make('duration_minutes')
                    ->label('Duration')
                    ->suffix(' mins'),
                Tables\Columns\TextColumn::make('price_hatchback')
                    ->label('Hatchback')
                    ->money('INR'),
                Tables\Columns\TextColumn::make('price_sedan')
                    ->label('Sedan')
                    ->money('INR'),
                Tables\Columns\TextColumn::make('price_suv')
                    ->label('SUV')
                    ->money('INR'),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->label('Active'),
            ])
            ->defaultSort('sort_order')
            ->filters([
                Tables\Filters\SelectFilter::make('service_category_id')
                    ->relationship('category', 'name')
                    ->label('Category'),
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
            'index' => Pages\ListServices::route('/'),
            'create' => Pages\CreateService::route('/create'),
            'edit' => Pages\EditService::route('/{record}/edit'),
        ];
    }
}
