<?php

namespace App\Livewire\Public;

use App\Domain\Normalizers\RegistrationNormalizer;
use App\Models\Lead;
use App\Models\Setting;
use Carbon\Carbon;
use Livewire\Component;

class HealthCheckLeadForm extends Component
{
    public string $name = '';
    public string $mobile = '';
    public string $email = '';
    public string $registrationNumber = '';
    public string $makeModel = '';
    public string $vehicleType = 'hatchback';
    public array $mainConcerns = [];
    public string $preferredDate = '';
    public string $preferredTime = 'morning'; // morning, afternoon, evening
    public string $notes = '';

    public bool $isSubmitted = false;
    public ?int $leadId = null;

    public array $availableConcerns = [
        'paint_swirls'       => 'Swirl Marks & Paint Haze',
        'scratches'          => 'Surface Scratches & Scuffs',
        'water_spots'        => 'Hard Water Spots & Scale',
        'interior_stains'    => 'Upholstery / Leather Stains',
        'ac_odour'           => 'AC & Cabin Odour',
        'wheels_tyres'       => 'Brake Dust & Tyre Fading',
        'glass_smears'       => 'Glass Scaling & Wiper Streaks',
        'ceramic_protection' => 'Ceramic Coating Health',
        'general_checkup'    => 'Overall 25-Point Diagnostic',
    ];

    public function mount(): void
    {
        $this->preferredDate = Carbon::tomorrow()->format('Y-m-d');
    }

    public function toggleConcern(string $concernKey): void
    {
        if (in_array($concernKey, $this->mainConcerns)) {
            $this->mainConcerns = array_values(array_diff($this->mainConcerns, [$concernKey]));
        } else {
            $this->mainConcerns[] = $concernKey;
        }
    }

    public function updatedMobile(): void
    {
        $clean = preg_replace('/[^0-9]/', '', $this->mobile);
        if (str_starts_with($clean, '91') && strlen($clean) === 12) {
            $clean = substr($clean, 2);
        } elseif (str_starts_with($clean, '0') && strlen($clean) === 11) {
            $clean = substr($clean, 1);
        }
        $this->mobile = $clean;
    }

    public function submit(): void
    {
        $this->updatedMobile();
        $this->registrationNumber = RegistrationNormalizer::normalize($this->registrationNumber);

        $this->validate([
            'name'               => 'required|string|min:2|max:100',
            'mobile'             => ['required', 'regex:/^[6-9][0-9]{9}$/'],
            'email'              => 'nullable|email|max:100',
            'registrationNumber' => 'required|string|min:4|max:20',
            'makeModel'          => 'required|string|min:2|max:100',
            'vehicleType'        => 'required|in:hatchback,sedan,suv,other',
            'mainConcerns'       => 'required|array|min:1',
            'preferredDate'      => 'required|date|after_or_equal:today',
            'preferredTime'      => 'required|in:morning,afternoon,evening',
            'notes'              => 'nullable|string|max:500',
        ], [
            'mobile.regex'         => 'Please enter a valid 10-digit Indian mobile number (e.g. 9876543210).',
            'mainConcerns.min'     => 'Please select at least one area of concern to inspect.',
            'makeModel.required'   => 'Please provide your vehicle make and model.',
        ]);

        $lead = Lead::create([
            'branch_id'           => 1,
            'source'              => 'health_check_form',
            'name'                => $this->name,
            'mobile'              => $this->mobile,
            'email'               => $this->email ?: null,
            'registration_number' => $this->registrationNumber,
            'make_model'          => $this->makeModel,
            'vehicle_type'        => $this->vehicleType,
            'main_concerns'       => $this->mainConcerns,
            'preferred_date'      => $this->preferredDate,
            'preferred_time'      => $this->preferredTime,
            'message'             => $this->notes ?: null,
            'status'              => 'new',
        ]);

        $this->leadId = $lead->id;
        $this->isSubmitted = true;

        $this->dispatch('generate_lead', [
            'lead_id' => $lead->id,
            'source'  => 'health_check_form',
            'type'    => 'free_car_health_check',
        ]);
    }

    public function getWhatsAppUrl(): string
    {
        $studioWhatsApp = Setting::get('business.whatsapp', '919419100000');
        $dateFormatted = Carbon::parse($this->preferredDate)->format('D, M j');
        $plate = RegistrationNormalizer::format($this->registrationNumber);

        $message = "Hi The Drive Clinic, I requested a *Free Digital Car Health Check* for my *{$this->makeModel} ({$plate})* on *{$dateFormatted} ({$this->preferredTime})*.\n\nLooking forward to getting my diagnostic report!";

        return 'https://wa.me/' . $studioWhatsApp . '?text=' . urlencode($message);
    }

    public function resetForm(): void
    {
        $this->reset(['name', 'mobile', 'email', 'registrationNumber', 'makeModel', 'mainConcerns', 'notes', 'isSubmitted', 'leadId']);
        $this->preferredDate = Carbon::tomorrow()->format('Y-m-d');
        $this->preferredTime = 'morning';
    }

    public function render()
    {
        return view('livewire.public.health-check-lead-form');
    }
}
