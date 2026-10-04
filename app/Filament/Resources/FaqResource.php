<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FaqResource\Pages;
use App\Models\Faq;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class FaqResource extends Resource
{
    protected static ?string $model = Faq::class;

    protected static ?string $navigationIcon = 'heroicon-o-question-mark-circle';
    protected static ?string $navigationGroup = 'Website & Marketing';
    protected static ?string $navigationLabel = 'FAQs';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('FAQ Content')
                    ->schema([
                        Forms\Components\TextInput::make('category')
                            ->required()
                            ->default('General')
                            ->placeholder('e.g. Wash, Detailing, Protection, General'),
                        Forms\Components\TextInput::make('sort_order')
                            ->numeric()
                            ->default(1),
                        Forms\Components\TextInput::make('question')
                            ->required()
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('answer')
                            ->required()
                            ->rows(4)
                            ->columnSpanFull(),
                        Forms\Components\Toggle::make('is_home')
                            ->label('Show on Homepage Accordion')
                            ->default(false),
                        Forms\Components\Toggle::make('is_active')
                            ->default(true),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('question')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->wrap(),
                Tables\Columns\TextColumn::make('category')
                    ->badge()
                    ->color('info')
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_home')
                    ->boolean()
                    ->label('Home'),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->label('Active'),
            ])
            ->defaultSort('sort_order')
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->options([
                        'General' => 'General',
                        'Wash' => 'Wash',
                        'Detailing' => 'Detailing',
                        'Protection' => 'Protection',
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
            'index' => Pages\ListFaqs::route('/'),
            'create' => Pages\CreateFaq::route('/create'),
            'edit' => Pages\EditFaq::route('/{record}/edit'),
        ];
    }
}
