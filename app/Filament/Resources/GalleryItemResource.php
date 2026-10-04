<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GalleryItemResource\Pages;
use App\Models\GalleryItem;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class GalleryItemResource extends Resource
{
    protected static ?string $model = GalleryItem::class;

    protected static ?string $navigationIcon = 'heroicon-o-photo';
    protected static ?string $navigationGroup = 'Website & Marketing';
    protected static ?string $navigationLabel = 'Before / After Gallery';
    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Gallery Transformation')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->placeholder('e.g. BMW M340i Paint Correction & 9H Ceramic'),
                        Forms\Components\TextInput::make('vehicle_model')
                            ->placeholder('e.g. BMW M340i LCI'),
                        Forms\Components\TextInput::make('service_category')
                            ->placeholder('e.g. Ceramic Coating, Detailing'),
                        Forms\Components\TextInput::make('before_image')
                            ->required()
                            ->placeholder('/images/gallery/before-1.webp or URL'),
                        Forms\Components\TextInput::make('after_image')
                            ->required()
                            ->placeholder('/images/gallery/after-1.webp or URL'),
                        Forms\Components\TextInput::make('sort_order')
                            ->numeric()
                            ->default(1),
                        Forms\Components\Textarea::make('description')
                            ->rows(2)
                            ->columnSpanFull(),
                        Forms\Components\Toggle::make('is_featured')
                            ->label('Show on Homepage Wipe Strip')
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
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('vehicle_model')
                    ->searchable(),
                Tables\Columns\TextColumn::make('service_category')
                    ->badge()
                    ->color('info'),
                Tables\Columns\IconColumn::make('is_featured')
                    ->boolean()
                    ->label('Home Page'),
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
            'index' => Pages\ListGalleryItems::route('/'),
            'create' => Pages\CreateGalleryItem::route('/create'),
            'edit' => Pages\EditGalleryItem::route('/{record}/edit'),
        ];
    }
}
