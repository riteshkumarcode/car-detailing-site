<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TestimonialResource\Pages;
use App\Models\Testimonial;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TestimonialResource extends Resource
{
    protected static ?string $model = Testimonial::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-bottom-center-text';
    protected static ?string $navigationGroup = 'Website & Marketing';
    protected static ?string $navigationLabel = 'Client Reviews';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Review Details')
                    ->schema([
                        Forms\Components\TextInput::make('author_name')
                            ->required(),
                        Forms\Components\TextInput::make('car_model')
                            ->placeholder('e.g. BMW M340i, Thar Roxx'),
                        Forms\Components\TextInput::make('service_taken')
                            ->placeholder('e.g. 9H Ceramic Coating, Foam Wash'),
                        Forms\Components\TextInput::make('rating')
                            ->numeric()
                            ->default(5)
                            ->minValue(1)
                            ->maxValue(5)
                            ->required(),
                        Forms\Components\Textarea::make('review_text')
                            ->required()
                            ->columnSpanFull()
                            ->rows(3),
                        Forms\Components\Toggle::make('is_featured')
                            ->label('Show on Homepage')
                            ->default(true),
                        Forms\Components\Toggle::make('is_active')
                            ->default(true),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('author_name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('car_model')
                    ->searchable(),
                Tables\Columns\TextColumn::make('service_taken'),
                Tables\Columns\TextColumn::make('rating')
                    ->badge()
                    ->color('warning')
                    ->suffix(' ★'),
                Tables\Columns\IconColumn::make('is_featured')
                    ->boolean()
                    ->label('Home Page'),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->label('Active'),
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
            'index' => Pages\ListTestimonials::route('/'),
            'create' => Pages\CreateTestimonial::route('/create'),
            'edit' => Pages\EditTestimonial::route('/{record}/edit'),
        ];
    }
}
