<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LeadResource\Pages;
use App\Models\Lead;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class LeadResource extends Resource
{
    protected static ?string $model = Lead::class;

    protected static ?string $navigationIcon = 'heroicon-o-inbox-arrow-down';
    protected static ?string $navigationGroup = 'Website & Marketing';
    protected static ?string $navigationLabel = 'Online Leads';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Inbound Lead Details')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required(),
                        Forms\Components\TextInput::make('mobile')
                            ->required(),
                        Forms\Components\TextInput::make('email')
                            ->email(),
                        Forms\Components\TextInput::make('registration_number')
                            ->label('Vehicle Plate'),
                        Forms\Components\TextInput::make('make_model')
                            ->label('Car Make & Model'),
                        Forms\Components\TextInput::make('vehicle_type'),
                        Forms\Components\TextInput::make('source')
                            ->badge(),
                        Forms\Components\DatePicker::make('preferred_date'),
                        Forms\Components\TextInput::make('preferred_time'),
                        Forms\Components\Textarea::make('message')
                            ->columnSpanFull()
                            ->rows(3),
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
                Tables\Columns\TextColumn::make('registration_number')
                    ->label('Plate #')
                    ->searchable()
                    ->fontFamily('mono'),
                Tables\Columns\TextColumn::make('make_model')
                    ->label('Vehicle'),
                Tables\Columns\TextColumn::make('source')
                    ->badge()
                    ->color('warning'),
                Tables\Columns\TextColumn::make('preferred_date')
                    ->date('d M Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Received')
                    ->dateTime('d M Y, h:i A')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
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
            'index' => Pages\ListLeads::route('/'),
            'edit' => Pages\EditLead::route('/{record}/edit'),
        ];
    }
}
