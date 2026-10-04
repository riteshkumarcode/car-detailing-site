<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomerResource\Pages;
use App\Models\Customer;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CustomerResource extends Resource
{
    protected static ?string $model = Customer::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationGroup = 'Studio Operations';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Customer Details')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(100),
                        Forms\Components\TextInput::make('mobile')
                            ->required()
                            ->tel()
                            ->maxLength(20),
                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->maxLength(100),
                        Forms\Components\TextInput::make('area')
                            ->maxLength(100)
                            ->placeholder('e.g. Gandhi Nagar, Jammu'),
                        Forms\Components\DatePicker::make('date_joined')
                            ->default(now()),
                        Forms\Components\Select::make('referral_source')
                            ->options([
                                'Walk-in' => 'Walk-in Drive-by',
                                'Google' => 'Google Search / Maps',
                                'Instagram' => 'Instagram',
                                'Referral' => 'Friend / Family Referral',
                                'Other' => 'Other',
                            ]),
                        Forms\Components\TagsInput::make('tags')
                            ->placeholder('New tag...'),
                        Forms\Components\Textarea::make('notes')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])->columns(2),
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
                Tables\Columns\TextColumn::make('mobile')
                    ->searchable()
                    ->copyable(),
                Tables\Columns\TextColumn::make('area')
                    ->searchable(),
                Tables\Columns\TextColumn::make('vehicles_count')
                    ->counts('vehicles')
                    ->label('Vehicles')
                    ->badge()
                    ->color('info'),
                Tables\Columns\TextColumn::make('total_spend')
                    ->label('Lifetime Spend')
                    ->money('INR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('date_joined')
                    ->date('d M Y')
                    ->sortable(),
            ])
            ->filters([
                //
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
            'index' => Pages\ListCustomers::route('/'),
            'create' => Pages\CreateCustomer::route('/create'),
            'edit' => Pages\EditCustomer::route('/{record}/edit'),
        ];
    }
}
