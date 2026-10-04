<?php

namespace App\Services;

use App\Models\Service;
use App\Models\Setting;

class HealthScoreCalculator
{
    /**
     * Standard 25-point inspection schema grouped by 5 core categories.
     */
    public static function getDefaultChecklistSchema(): array
    {
        return [
            'exterior' => [
                'name' => 'Exterior Paint & Body',
                'weight_key' => 'exterior',
                'items' => [
                    'swirl_marks'         => 'Swirl Marks & Holograms',
                    'scratches'           => 'Surface Scratches & Scuffs',
                    'water_spots'         => 'Hard Water Spot Stains',
                    'oxidation'           => 'Clear Coat Oxidation & Haze',
                    'paint_contamination' => 'Tar & Industrial Fall-out',
                    'gloss_finish'        => 'Reflectivity & Gloss Uniformity',
                ],
            ],
            'interior' => [
                'name' => 'Interior Cabin & Upholstery',
                'weight_key' => 'interior',
                'items' => [
                    'seats'       => 'Seats (Fabric / Leather Condition)',
                    'carpet'      => 'Floor Carpeting & Mats',
                    'floor_mats'  => 'Footwell Soil & Mud Ingress',
                    'dashboard'   => 'Dashboard & Center Console UV Condition',
                    'door_panels' => 'Door Trim & Armrests',
                    'ac_vents'    => 'AC Duct Dust & Dirt Buildup',
                    'odour'       => 'Cabin Odour & Bacteria Level',
                    'stains'      => 'Liquid & Food Spot Stains',
                ],
            ],
            'wheels' => [
                'name' => 'Wheels, Tyres & Arches',
                'weight_key' => 'wheels',
                'items' => [
                    'tyre_condition' => 'Tyre Sidewall Hydration & Dressing',
                    'tyre_pressure'  => 'Visual Pressure & Bulges',
                    'tread'          => 'Tread Depth Uniformity',
                    'brake_dust'     => 'Iron Brake Dust Contamination',
                    'wheel_condition'=> 'Alloy Wheel Faces & Barrel Cleanliness',
                ],
            ],
            'glass' => [
                'name' => 'Glass & Visibility',
                'weight_key' => 'glass',
                'items' => [
                    'windshield'      => 'Front Windshield Clarity',
                    'side_glass'      => 'Side & Rear Windows',
                    'water_spots'     => 'Mineral Water Spots on Glass',
                    'smearing'        => 'Oil Film & Traffic Film',
                    'wiper_condition' => 'Wiper Blade Drag & Arc Marks',
                ],
            ],
            'protection' => [
                'name' => 'Protective Coating Health',
                'weight_key' => 'protection',
                'is_single_choice' => true,
                'items' => [
                    'protection_status' => 'Hydrophobic Layer Status',
                ],
            ],
        ];
    }

    /**
     * Get active category weights from settings.
     */
    public function getActiveWeights(): array
    {
        return (array) Setting::get('health_check.weights', [
            'exterior'   => 30,
            'interior'   => 30,
            'wheels'     => 15,
            'glass'      => 15,
            'protection' => 10,
        ]);
    }

    /**
     * Convert item rating text to numerical points (Good=2, Fair=1, Needs Attention=0).
     */
    public function ratingToPoints(?string $rating): int
    {
        return match (strtolower((string) $rating)) {
            'good' => 2,
            'fair' => 1,
            default => 0, // 'needs_attention' or empty
        };
    }

    /**
     * Convert protection type choice to points (Ceramic=2, Wax/Sealant=1, None/Unknown=0).
     */
    public function protectionToPoints(string $protectionType): int
    {
        return match (strtolower($protectionType)) {
            'ceramic' => 2,
            'wax', 'sealant' => 1,
            default => 0, // 'none', 'unknown'
        };
    }

    /**
     * Compute exact 0-100 Drive Health Score.
     */
    public function calculateScore(array $checklistData, string $protectionType, ?array $customWeights = null): array
    {
        $schema = self::getDefaultChecklistSchema();
        $weights = $customWeights ?: $this->getActiveWeights();

        $categoryScores = [];
        $maxPointsMap = [];
        $earnedPointsMap = [];
        $totalCalculatedScore = 0.0;

        foreach ($schema as $catKey => $catMeta) {
            $catWeight = (float) ($weights[$catKey] ?? 0);

            if ($catKey === 'protection') {
                $earned = $this->protectionToPoints($protectionType);
                $maxPoints = 2; // single choice max
            } else {
                $items = $catMeta['items'];
                $maxPoints = count($items) * 2;
                $earned = 0;

                foreach ($items as $itemKey => $itemLabel) {
                    $rating = $checklistData[$catKey][$itemKey]['rating'] ?? 'needs_attention';
                    $earned += $this->ratingToPoints($rating);
                }
            }

            $earnedPointsMap[$catKey] = $earned;
            $maxPointsMap[$catKey] = $maxPoints;

            // Category score formula = (earned / max) * weight
            $catScore = $maxPoints > 0 ? ($earned / $maxPoints) * $catWeight : 0.0;
            $categoryScores[$catKey] = round($catScore, 1);
            $totalCalculatedScore += $catScore;
        }

        $overallScore = (int) round(min(100, max(0, $totalCalculatedScore)));

        return [
            'overall_score'    => $overallScore,
            'category_scores'  => $categoryScores,
            'weights_snapshot' => $weights,
            'earned_points'    => $earnedPointsMap,
            'max_points'       => $maxPointsMap,
        ];
    }

    /**
     * Generate automated service recommendations based on checklist findings.
     */
    public function generateRecommendations(array $checklistData, string $protectionType, string $vehicleType = 'hatchback'): array
    {
        $recommendedToday = [];
        $recommendedLater = [];

        // Helper to fetch service pricing safely
        $getServiceData = function (string $slug) use ($vehicleType) {
            $service = Service::where('slug', $slug)->first();
            if (!$service) {
                return null;
            }
            return [
                'service_id'   => $service->id,
                'name'         => $service->name,
                'slug'         => $service->slug,
                'duration'     => $service->duration_minutes,
                'price'        => $service->getPriceForVehicleType($vehicleType) ?? 499,
                'vehicle_type' => $vehicleType,
            ];
        };

        // 1. Exterior Paint findings
        $ext = $checklistData['exterior'] ?? [];
        $hasPaintIssues = ($ext['swirl_marks']['rating'] ?? '') === 'needs_attention'
            || ($ext['scratches']['rating'] ?? '') === 'needs_attention'
            || ($ext['oxidation']['rating'] ?? '') === 'needs_attention';

        if ($hasPaintIssues) {
            $srv = $getServiceData('paint-correction-gloss') ?: $getServiceData('essential-foam-wash');
            if ($srv) {
                $srv['reason'] = 'Paint has visible swirl marks or micro-scratches requiring machine compound & polish.';
                $recommendedToday[] = $srv;
            }
        }

        // 2. Interior findings
        $interior = $checklistData['interior'] ?? [];
        $hasInteriorIssues = ($interior['stains']['rating'] ?? '') === 'needs_attention'
            || ($interior['odour']['rating'] ?? '') === 'needs_attention'
            || ($interior['seats']['rating'] ?? '') === 'needs_attention';

        if ($hasInteriorIssues) {
            $srv = $getServiceData('interior-deep-clean') ?: $getServiceData('essential-foam-wash');
            if ($srv) {
                $srv['reason'] = 'Upholstery stains and cabin vents require 140°C steam extraction disinfection.';
                $recommendedToday[] = $srv;
            }
        }

        // 3. Protection status
        if (in_array(strtolower($protectionType), ['none', 'unknown', 'needs_attention'])) {
            $srv = $getServiceData('ceramic-coating-9h');
            if ($srv) {
                $srv['reason'] = 'No hydrophobic ceramic barrier detected. 9H ceramic shield recommended for Jammu climate.';
                $recommendedLater[] = $srv;
            }
        }

        // 4. Glass findings
        $glass = $checklistData['glass'] ?? [];
        if (($glass['water_spots']['rating'] ?? '') === 'needs_attention' || ($glass['smearing']['rating'] ?? '') === 'needs_attention') {
            $srv = $getServiceData('glass-scaling-treatment') ?: [
                'name' => 'Glass Hard Water Scale Stripping',
                'price' => 799,
                'duration' => 30,
                'reason' => 'Mineral scale on windshield causing night wiper glare.',
            ];
            $recommendedLater[] = $srv;
        }

        // Default baseline maintenance wash if no priority today
        if (empty($recommendedToday)) {
            $srv = $getServiceData('essential-foam-wash');
            if ($srv) {
                $srv['reason'] = 'Standard 2-bucket clinic maintenance wash to preserve cosmetic health.';
                $recommendedToday[] = $srv;
            }
        }

        return [
            'today' => $recommendedToday,
            'later' => $recommendedLater,
        ];
    }
}
