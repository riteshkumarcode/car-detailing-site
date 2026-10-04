<?php

namespace App\Livewire\Staff;

use App\Models\MembershipPlan;
use App\Models\Service;
use App\Models\ServiceCategory;
use Illuminate\Support\Str;
use Livewire\Component;

class MembershipPlanManager extends Component
{
    public bool $isModalOpen = false;
    public ?int $editingPlanId = null;

    public string $name = '';
    public string $slug = '';
    public float $price = 0.0;
    public int $duration_days = 365;
    public string $billing_frequency = 'yearly';
    public string $benefits_description = '';
    public bool $is_featured = false;
    public bool $is_active = true;
    public int $sort_order = 1;

    // Included services list: [['service_id' => 1, 'service_name' => 'Wash', 'count' => 12]]
    public array $included_services = [];
    public ?int $addServiceId = null;
    public int $addServiceCount = 12;

    // Category discounts: ['detailing' => 10, 'protection' => 5]
    public array $category_discounts = [];
    public string $addDiscountCategory = 'detailing';
    public int $addDiscountPercent = 10;

    // Features bullets
    public array $features = [];
    public string $newFeatureText = '';

    public ?string $feedbackMessage = null;

    protected $rules = [
        'name'          => 'required|string|max:100',
        'price'         => 'required|numeric|min:0',
        'duration_days' => 'required|integer|min:1',
    ];

    public function openCreate(): void
    {
        $this->reset([
            'editingPlanId', 'name', 'slug', 'price', 'benefits_description',
            'is_featured', 'sort_order', 'included_services', 'category_discounts',
            'features', 'newFeatureText', 'feedbackMessage'
        ]);
        $this->duration_days = 365;
        $this->billing_frequency = 'yearly';
        $this->is_active = true;
        $this->isModalOpen = true;
    }

    public function openEdit(int $planId): void
    {
        $plan = MembershipPlan::findOrFail($planId);
        $this->editingPlanId = $plan->id;
        $this->name = $plan->name;
        $this->slug = $plan->slug;
        $this->price = (float) $plan->price;
        $this->duration_days = $plan->duration_days;
        $this->billing_frequency = $plan->billing_frequency ?? 'yearly';
        $this->benefits_description = $plan->benefits_description ?? '';
        $this->is_featured = (bool) $plan->is_featured;
        $this->is_active = (bool) $plan->is_active;
        $this->sort_order = $plan->sort_order ?? 1;
        $this->included_services = $plan->included_services ?? [];
        $this->category_discounts = $plan->category_discounts ?? [];
        $this->features = $plan->features ?? [];
        $this->feedbackMessage = null;

        $this->isModalOpen = true;
    }

    public function addIncludedService(): void
    {
        if (!$this->addServiceId) {
            return;
        }

        $service = Service::find($this->addServiceId);
        if (!$service) {
            return;
        }

        $this->included_services[] = [
            'service_id'   => $service->id,
            'service_name' => $service->name,
            'count'        => max(1, $this->addServiceCount),
        ];

        $this->addServiceId = null;
        $this->addServiceCount = 12;
    }

    public function removeIncludedService(int $index): void
    {
        if (isset($this->included_services[$index])) {
            unset($this->included_services[$index]);
            $this->included_services = array_values($this->included_services);
        }
    }

    public function addCategoryDiscount(): void
    {
        if (!empty($this->addDiscountCategory) && $this->addDiscountPercent > 0) {
            $this->category_discounts[strtolower(trim($this->addDiscountCategory))] = (int) $this->addDiscountPercent;
            $this->addDiscountPercent = 10;
        }
    }

    public function removeCategoryDiscount(string $category): void
    {
        unset($this->category_discounts[$category]);
    }

    public function addFeature(): void
    {
        $trimmed = trim($this->newFeatureText);
        if (!empty($trimmed)) {
            $this->features[] = $trimmed;
            $this->newFeatureText = '';
        }
    }

    public function removeFeature(int $index): void
    {
        if (isset($this->features[$index])) {
            unset($this->features[$index]);
            $this->features = array_values($this->features);
        }
    }

    public function toggleActive(int $planId): void
    {
        $plan = MembershipPlan::findOrFail($planId);
        $plan->is_active = !$plan->is_active;
        $plan->save();

        $this->feedbackMessage = "Plan '{$plan->name}' " . ($plan->is_active ? 'activated.' : 'deactivated. Existing members remain active.');
    }

    public function savePlan(): void
    {
        $this->validate();

        $slug = $this->slug ?: Str::slug($this->name);

        $payload = [
            'branch_id'            => 1,
            'name'                 => $this->name,
            'slug'                 => $slug,
            'price'                => $this->price,
            'duration_days'        => $this->duration_days,
            'billing_frequency'    => $this->billing_frequency,
            'included_services'    => $this->included_services,
            'category_discounts'   => $this->category_discounts,
            'features'             => $this->features,
            'benefits_description' => $this->benefits_description,
            'is_featured'          => $this->is_featured,
            'is_active'            => $this->is_active,
            'sort_order'           => $this->sort_order,
        ];

        if ($this->editingPlanId) {
            $plan = MembershipPlan::findOrFail($this->editingPlanId);
            $plan->update($payload);
            $this->feedbackMessage = "Plan '{$plan->name}' updated successfully.";
        } else {
            MembershipPlan::create($payload);
            $this->feedbackMessage = "New plan '{$this->name}' created successfully.";
        }

        $this->isModalOpen = false;
    }

    public function render()
    {
        $plans = MembershipPlan::orderBy('sort_order')->orderBy('price')->get();
        $services = Service::where('is_active', true)->orderBy('name')->get();
        $categories = ServiceCategory::orderBy('name')->get();

        return view('livewire.staff.membership-plan-manager', [
            'plans'      => $plans,
            'services'   => $services,
            'categories' => $categories,
        ]);
    }
}
