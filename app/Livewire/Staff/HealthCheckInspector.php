<?php

namespace App\Livewire\Staff;

use App\Models\Customer;
use App\Models\HealthCheck;
use App\Models\Service;
use App\Models\Vehicle;
use App\Services\HealthScoreCalculator;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithFileUploads;

class HealthCheckInspector extends Component
{
    use WithFileUploads;

    public ?int $vehicleId = null;
    public ?int $customerId = null;
    public ?int $bookingId = null;

    // Active schema
    public array $checklistSchema = [];

    // State
    public array $checklistData = [];
    public string $protectionType = 'unknown';
    public string $technicianNotes = '';

    // Step in inspection
    public int $step = 1; // 1: Inspection, 2: Recommendations Review, 3: Completed Card

    // Dynamic Live Score
    public int $liveScore = 0;
    public array $liveCategoryScores = [];

    // Editable Recommendations
    public array $recommendedToday = [];
    public array $recommendedLater = [];

    // Saved Check
    public ?HealthCheck $savedCheck = null;

    // Temporary photo uploads
    public $tempPhotos = [];

    public function mount(?int $vehicleId = null, ?int $customerId = null, ?int $bookingId = null, HealthScoreCalculator $calculator = null): void
    {
        $this->vehicleId = $vehicleId;
        $this->customerId = $customerId;
        $this->bookingId = $bookingId;

        // If vehicle is provided but customer isn't, link customer
        if ($this->vehicleId && !$this->customerId) {
            $veh = Vehicle::find($this->vehicleId);
            if ($veh) {
                $this->customerId = $veh->customer_id;
            }
        }

        $this->checklistSchema = HealthScoreCalculator::getDefaultChecklistSchema();

        // Initialize default checklist answers (Default all to 'good')
        foreach ($this->checklistSchema as $catKey => $catMeta) {
            if ($catKey === 'protection') {
                continue;
            }
            foreach ($catMeta['items'] as $itemKey => $itemLabel) {
                $this->checklistData[$catKey][$itemKey] = [
                    'rating' => 'good',
                    'note'   => '',
                    'photo'  => null,
                ];
            }
        }

        $this->recalculateLiveScore($calculator ?: app(HealthScoreCalculator::class));
    }

    public function setItemRating(string $category, string $item, string $rating): void
    {
        $this->checklistData[$category][$item]['rating'] = $rating;
        $this->recalculateLiveScore(app(HealthScoreCalculator::class));
    }

    public function setProtectionType(string $type): void
    {
        $this->protectionType = $type;
        $this->recalculateLiveScore(app(HealthScoreCalculator::class));
    }

    public function recalculateLiveScore(HealthScoreCalculator $calculator): void
    {
        $calc = $calculator->calculateScore($this->checklistData, $this->protectionType);
        $this->liveScore = $calc['overall_score'];
        $this->liveCategoryScores = $calc['category_scores'];
    }

    public function proceedToRecommendations(HealthScoreCalculator $calculator): void
    {
        $vehicle = Vehicle::find($this->vehicleId);
        $vehicleType = $vehicle ? $vehicle->vehicle_type : 'hatchback';

        $recs = $calculator->generateRecommendations($this->checklistData, $this->protectionType, $vehicleType);
        $this->recommendedToday = $recs['today'];
        $this->recommendedLater = $recs['later'];

        $this->step = 2;
    }

    public function removeRecommendation(string $bucket, int $index): void
    {
        if ($bucket === 'today' && isset($this->recommendedToday[$index])) {
            unset($this->recommendedToday[$index]);
            $this->recommendedToday = array_values($this->recommendedToday);
        } elseif ($bucket === 'later' && isset($this->recommendedLater[$index])) {
            unset($this->recommendedLater[$index]);
            $this->recommendedLater = array_values($this->recommendedLater);
        }
    }

    public function addRecommendation(string $bucket, int $serviceId): void
    {
        $service = Service::find($serviceId);
        if (!$service) {
            return;
        }

        $vehicle = Vehicle::find($this->vehicleId);
        $vehicleType = $vehicle ? $vehicle->vehicle_type : 'hatchback';

        $item = [
            'service_id'   => $service->id,
            'name'         => $service->name,
            'slug'         => $service->slug,
            'duration'     => $service->duration_minutes,
            'price'        => $service->getPriceForVehicleType($vehicleType) ?? 499,
            'vehicle_type' => $vehicleType,
            'reason'       => 'Staff added manual recommendation.',
        ];

        if ($bucket === 'today') {
            $this->recommendedToday[] = $item;
        } else {
            $this->recommendedLater[] = $item;
        }
    }

    public function saveHealthCheck(HealthScoreCalculator $calculator): void
    {
        $this->validate([
            'vehicleId'  => 'required|exists:vehicles,id',
            'customerId' => 'required|exists:customers,id',
        ]);

        $scoreResult = $calculator->calculateScore($this->checklistData, $this->protectionType);

        $healthCheck = HealthCheck::create([
            'branch_id'          => 1,
            'vehicle_id'         => $this->vehicleId,
            'customer_id'        => $this->customerId,
            'booking_id'         => $this->bookingId,
            'user_id'            => auth()->id() ?? null,
            'check_date'         => Carbon::today()->format('Y-m-d'),
            'overall_score'      => $scoreResult['overall_score'],
            'protection_type'    => $this->protectionType,
            'category_scores'    => $scoreResult['category_scores'],
            'weights_snapshot'   => $scoreResult['weights_snapshot'],
            'checklist_data'     => $this->checklistData,
            'recommended_today'  => $this->recommendedToday,
            'recommended_later'  => $this->recommendedLater,
            'technician_notes'   => $this->technicianNotes,
        ]);

        $this->savedCheck = $healthCheck;
        $this->step = 3;
    }

    public function render()
    {
        $vehicle = $this->vehicleId ? Vehicle::with('customer')->find($this->vehicleId) : null;
        $allServices = Service::where('is_active', true)->orderBy('sort_order')->get();
        $disclaimer = HealthCheck::getMandatoryDisclaimer();

        return view('livewire.staff.health-check-inspector', [
            'vehicle'     => $vehicle,
            'allServices' => $allServices,
            'disclaimer'  => $disclaimer,
        ]);
    }
}
