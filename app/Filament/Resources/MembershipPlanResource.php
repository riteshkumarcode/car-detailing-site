<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MembershipPlanResource\Pages;
use App\Models\MembershipPlan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class MembershipPlanResource extends Resource
{
    protected static ?string $model = MembershipPlan::class;

    protected static ?string $navigationIcon = 'heroicon-o-star';
    protected static ?string $navigationGroup = 'Drive Club Memberships';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Plan Structure')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug($state))),
                        Forms\Components\TextInput::make('slug')
                            ->required()
                            ->unique(ignoreRecord: true),
                        Forms\Components\TextInput::make('price')
                            ->numeric()
                            ->prefix('₹')
                            ->required(),
                        Forms\Components\TextInput::make('duration_days')
                            ->numeric()
                            ->default(365)
                            ->required(),
                        Forms\Components\Select::make('billing_frequency')
                            ->options([
                                'yearly'      => 'Yearly',
                                'half-yearly' => 'Half-Yearly',
                                'monthly'     => 'Monthly',
                            ])
                            ->default('yearly')
                            ->required(),
                        Forms\Components\TextInput::make('sort_order')
                            ->numeric()
                            ->default(1),
                        Forms\Components\Toggle::make('is_featured')
                            ->label('Featured Tier (Most Popular)'),
                        Forms\Components\Toggle::make('is_active')
                            ->label('Active for New Enrollments')
                            ->default(true),
                        Forms\Components\Textarea::make('benefits_description')
                            ->columnSpanFull()
                            ->rows(2),
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
                Tables\Columns\TextColumn::make('price')
                    ->money('INR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('billing_frequency')
                    ->badge()
                    ->uppercase(),
                Tables\Columns\TextColumn::make('duration_days')
                    ->suffix(' days'),
                Tables\Columns\IconColumn::make('is_featured')
                    ->boolean()
                    ->label('Popular'),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->label('Active'),
            ])
            ->defaultSort('sort_order')
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
            'index' => Pages\ListMembershipPlans::route('/'),
            'create' => Pages\CreateMembershipPlan::route('/create'),
            'edit' => Pages\EditMembershipPlan::route('/{record}/edit'),
        ];
    }
}
