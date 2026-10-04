<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;

class ManageSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?string $navigationGroup = 'Administration';
    protected static ?string $navigationLabel = 'Studio Settings';
    protected static ?string $title = 'Studio & System Settings';
    protected static ?int $navigationSort = 99;
    protected static string $view = 'filament.pages.manage-settings';

    public ?array $data = [];

    public static function canAccess(): bool
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        return $user && ($user->isOwner() || $user->can('manage_settings'));
    }

    public function mount(): void
    {
        $this->form->fill([
            'business_name' => Setting::get('business.name', 'The Drive Clinic'),
            'business_tagline' => Setting::get('business.tagline', "Your Car's Healthcare Centre"),
            'business_address' => Setting::get('business.address', '[Nanak Nagar, Jammu, J&K 180004]'),
            'business_phone' => Setting::get('business.phone', '[+91 94191 00000]'),
            'business_whatsapp' => Setting::get('business.whatsapp', '919419100000'),
            'business_email' => Setting::get('business.email', 'contact@thedriveclinic.in'),
            'business_google_review_url' => Setting::get('business.google_review_url', 'https://g.page/r/thedriveclinic/review'),
            
            'capacity_bays' => Setting::get('capacity.bays', 3),
            'capacity_slot_length_minutes' => Setting::get('capacity.slot_length_minutes', 30),
            
            'billing_invoice_prefix' => Setting::get('billing.invoice_prefix', 'TDC'),
            'billing_invoice_format' => Setting::get('billing.invoice_format', 'TDC/{FY}/{SEQ4}'),
            'billing_gst_enabled' => Setting::get('billing.gst_enabled', false),
            'billing_gstin' => Setting::get('billing.gstin', '[01AAAAA0000A1Z5]'),
            'billing_cgst_rate' => Setting::get('billing.cgst_rate', 9.0),
            'billing_sgst_rate' => Setting::get('billing.sgst_rate', 9.0),
            'billing_max_staff_discount_percent' => Setting::get('billing.max_staff_discount_percent', 5),

            'health_check_weight_exterior' => Setting::get('health_check.weights.exterior', 30),
            'health_check_weight_interior' => Setting::get('health_check.weights.interior', 30),
            'health_check_weight_wheels' => Setting::get('health_check.weights.wheels', 15),
            'health_check_weight_glass' => Setting::get('health_check.weights.glass', 15),
            'health_check_weight_protection' => Setting::get('health_check.weights.protection', 10),
            'health_check_disclaimer' => Setting::get('health_check.disclaimer', 'The Drive Health Score describes cosmetic condition only. It is not a certified mechanical, roadworthiness or safety inspection.'),

            'announcement_bar_text' => Setting::get('website.announcement_bar_text', 'Grand Opening in Nanak Nagar, Jammu! Get a Free Digital Car Health Check.'),
            'announcement_bar_link' => Setting::get('website.announcement_bar_link', '/free-car-health-check'),
            'announcement_bar_active' => Setting::get('website.announcement_bar_active', true),
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Studio Business Information')
                    ->description('Contact and location details shown across the website and invoices.')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('business_name')->required()->label('Business Name'),
                            TextInput::make('business_tagline')->required()->label('Tagline'),
                            TextInput::make('business_phone')->required()->label('Phone Display'),
                            TextInput::make('business_whatsapp')->required()->label('WhatsApp Number (with country code)'),
                            TextInput::make('business_email')->email()->required()->label('Contact Email'),
                            TextInput::make('business_google_review_url')->url()->label('Google Review Link'),
                        ]),
                        Textarea::make('business_address')->required()->rows(2)->label('Studio Address'),
                    ]),

                Section::make('Capacity & Bay Management')
                    ->description('Controls live availability for online booking and walk-in scheduling.')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('capacity_bays')
                                ->numeric()
                                ->minValue(1)
                                ->maxValue(10)
                                ->required()
                                ->label('Active Bay Capacity (Cars at once)'),
                            TextInput::make('capacity_slot_length_minutes')
                                ->numeric()
                                ->default(30)
                                ->required()
                                ->label('Grid Slot Length (Minutes)'),
                        ]),
                    ]),

                Section::make('Billing & Indian GST Settings')
                    ->description('Configure FY invoice numbering and tax breakdown rules.')
                    ->schema([
                        Grid::make(3)->schema([
                            TextInput::make('billing_invoice_prefix')->required()->label('Invoice Prefix'),
                            TextInput::make('billing_invoice_format')->required()->label('Sequence Format (e.g. TDC/{FY}/{SEQ4})'),
                            TextInput::make('billing_max_staff_discount_percent')->numeric()->label('Max Staff Discount (%)'),
                        ]),
                        Toggle::make('billing_gst_enabled')
                            ->label('Enable GST Breakdown on Invoices')
                            ->reactive(),
                        Grid::make(3)->schema([
                            TextInput::make('billing_gstin')->label('GSTIN')->visible(fn ($get) => $get('billing_gst_enabled')),
                            TextInput::make('billing_cgst_rate')->numeric()->label('CGST Rate (%)')->visible(fn ($get) => $get('billing_gst_enabled')),
                            TextInput::make('billing_sgst_rate')->numeric()->label('SGST Rate (%)')->visible(fn ($get) => $get('billing_gst_enabled')),
                        ]),
                    ]),

                Section::make('Digital Car Health Check Formula')
                    ->description('Weights assigned to diagnostic categories. Sum should equal 100.')
                    ->schema([
                        Grid::make(5)->schema([
                            TextInput::make('health_check_weight_exterior')->numeric()->required()->label('Exterior / Paint'),
                            TextInput::make('health_check_weight_interior')->numeric()->required()->label('Interior'),
                            TextInput::make('health_check_weight_wheels')->numeric()->required()->label('Wheels & Tyres'),
                            TextInput::make('health_check_weight_glass')->numeric()->required()->label('Glass'),
                            TextInput::make('health_check_weight_protection')->numeric()->required()->label('Protection'),
                        ]),
                        Textarea::make('health_check_disclaimer')->required()->rows(2)->label('Mandatory Cosmetic Disclaimer'),
                    ]),

                Section::make('Website Announcement Bar')
                    ->schema([
                        Toggle::make('announcement_bar_active')->label('Show Announcement Bar on Public Site'),
                        TextInput::make('announcement_bar_text')->label('Announcement Text'),
                        TextInput::make('announcement_bar_link')->label('Target URL / Slug'),
                    ]),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $state = $this->form->getState();

        Setting::set('business.name', $state['business_name'], 'business');
        Setting::set('business.tagline', $state['business_tagline'], 'business');
        Setting::set('business.address', $state['business_address'], 'business');
        Setting::set('business.phone', $state['business_phone'], 'business');
        Setting::set('business.whatsapp', $state['business_whatsapp'], 'business');
        Setting::set('business.email', $state['business_email'], 'business');
        Setting::set('business.google_review_url', $state['business_google_review_url'], 'business');

        Setting::set('capacity.bays', (int)$state['capacity_bays'], 'capacity');
        Setting::set('capacity.slot_length_minutes', (int)$state['capacity_slot_length_minutes'], 'capacity');

        Setting::set('billing.invoice_prefix', $state['billing_invoice_prefix'], 'billing');
        Setting::set('billing.invoice_format', $state['billing_invoice_format'], 'billing');
        Setting::set('billing.gst_enabled', (bool)$state['billing_gst_enabled'], 'billing');
        Setting::set('billing.gstin', $state['billing_gstin'], 'billing');
        Setting::set('billing.cgst_rate', (float)$state['billing_cgst_rate'], 'billing');
        Setting::set('billing.sgst_rate', (float)$state['billing_sgst_rate'], 'billing');
        Setting::set('billing.max_staff_discount_percent', (int)$state['billing_max_staff_discount_percent'], 'billing');

        Setting::set('health_check.weights', [
            'exterior'   => (int)$state['health_check_weight_exterior'],
            'interior'   => (int)$state['health_check_weight_interior'],
            'wheels'     => (int)$state['health_check_weight_wheels'],
            'glass'      => (int)$state['health_check_weight_glass'],
            'protection' => (int)$state['health_check_weight_protection'],
        ], 'health_check');
        Setting::set('health_check.disclaimer', $state['health_check_disclaimer'], 'health_check');

        Setting::set('website.announcement_bar_text', $state['announcement_bar_text'], 'website');
        Setting::set('website.announcement_bar_link', $state['announcement_bar_link'], 'website');
        Setting::set('website.announcement_bar_active', (bool)$state['announcement_bar_active'], 'website');

        Notification::make()
            ->title('Settings Saved Successfully')
            ->success()
            ->send();
    }
}
