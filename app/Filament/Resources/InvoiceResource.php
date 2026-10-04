<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InvoiceResource\Pages;
use App\Models\Invoice;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class InvoiceResource extends Resource
{
    protected static ?string $model = Invoice::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationGroup = 'Billing & Accounts';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Invoice Overview')
                    ->schema([
                        Forms\Components\TextInput::make('invoice_number')
                            ->disabled()
                            ->label('Invoice #'),
                        Forms\Components\Select::make('customer_id')
                            ->relationship('customer', 'name')
                            ->disabled()
                            ->label('Customer'),
                        Forms\Components\Select::make('vehicle_id')
                            ->relationship('vehicle', 'registration_number')
                            ->disabled()
                            ->label('Vehicle Plate'),
                        Forms\Components\TextInput::make('payment_method')
                            ->disabled()
                            ->label('Payment Method'),
                        Forms\Components\TextInput::make('subtotal')
                            ->numeric()
                            ->disabled()
                            ->prefix('₹'),
                        Forms\Components\TextInput::make('discount_amount')
                            ->numeric()
                            ->disabled()
                            ->prefix('₹'),
                        Forms\Components\TextInput::make('total_tax')
                            ->numeric()
                            ->disabled()
                            ->prefix('₹'),
                        Forms\Components\TextInput::make('total_amount')
                            ->numeric()
                            ->disabled()
                            ->prefix('₹'),
                        Forms\Components\TextInput::make('status')
                            ->disabled()
                            ->label('Status'),
                    ])->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('invoice_number')
                    ->label('Invoice #')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->fontFamily('mono'),
                Tables\Columns\TextColumn::make('customer.name')
                    ->label('Customer')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('vehicle.registration_number')
                    ->label('Vehicle Plate')
                    ->searchable()
                    ->fontFamily('mono'),
                Tables\Columns\TextColumn::make('payment_method')
                    ->badge()
                    ->uppercase(),
                Tables\Columns\TextColumn::make('total_amount')
                    ->label('Total Paid')
                    ->money('INR')
                    ->sortable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'issued' => 'success',
                        'cancelled' => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Date')
                    ->dateTime('d M Y, h:i A')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'issued' => 'Issued',
                        'cancelled' => 'Cancelled',
                    ]),
                Tables\Filters\SelectFilter::make('payment_method')
                    ->options([
                        'cash' => 'Cash',
                        'upi'  => 'UPI',
                        'card' => 'Card',
                    ]),
            ])
            ->actions([
                Tables\Actions\Action::make('view_pdf')
                    ->label('PDF')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->url(fn (Invoice $record): string => route('invoices.pdf', $record->share_token))
                    ->openUrlInNewTab(),
                Tables\Actions\Action::make('view_online')
                    ->label('View')
                    ->icon('heroicon-o-arrow-top-right-on-square')
                    ->url(fn (Invoice $record): string => route('invoices.show', $record->share_token))
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
            'index' => Pages\ListInvoices::route('/'),
        ];
    }
}
