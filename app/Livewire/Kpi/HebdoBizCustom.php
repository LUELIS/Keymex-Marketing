<?php

namespace App\Livewire\Kpi;

use App\Services\MongoPropertyService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class HebdoBizCustom extends Component
{
    public bool $mongoDbError = false;
    public string $startDate = '';
    public string $endDate = '';

    protected MongoPropertyService $propertyService;

    protected $queryString = [
        'startDate' => ['except' => ''],
        'endDate' => ['except' => ''],
    ];

    // Cache duration: 5 minutes
    protected int $cacheTtl = 300;

    public function boot(MongoPropertyService $propertyService): void
    {
        $this->propertyService = $propertyService;
    }

    public function mount(): void
    {
        // Default to current month if no dates specified
        if (empty($this->startDate)) {
            $this->startDate = Carbon::now()->startOfMonth()->format('Y-m-d');
        }
        if (empty($this->endDate)) {
            $this->endDate = Carbon::now()->format('Y-m-d');
        }
    }

    public function updatedStartDate(): void
    {
        // Ensure end date is not before start date
        if ($this->endDate && $this->startDate && $this->endDate < $this->startDate) {
            $this->endDate = $this->startDate;
        }
    }

    public function updatedEndDate(): void
    {
        // Ensure start date is not after end date
        if ($this->startDate && $this->endDate && $this->startDate > $this->endDate) {
            $this->startDate = $this->endDate;
        }
    }

    public function setPreset(string $preset): void
    {
        $now = Carbon::now();

        switch ($preset) {
            case 'thisWeek':
                $this->startDate = $now->copy()->startOfWeek(Carbon::MONDAY)->format('Y-m-d');
                $this->endDate = $now->format('Y-m-d');
                break;
            case 'lastWeek':
                $lastWeek = $now->copy()->subWeek();
                $this->startDate = $lastWeek->copy()->startOfWeek(Carbon::MONDAY)->format('Y-m-d');
                $this->endDate = $lastWeek->copy()->endOfWeek(Carbon::SUNDAY)->format('Y-m-d');
                break;
            case 'thisMonth':
                $this->startDate = $now->copy()->startOfMonth()->format('Y-m-d');
                $this->endDate = $now->format('Y-m-d');
                break;
            case 'lastMonth':
                $lastMonth = $now->copy()->subMonth();
                $this->startDate = $lastMonth->copy()->startOfMonth()->format('Y-m-d');
                $this->endDate = $lastMonth->copy()->endOfMonth()->format('Y-m-d');
                break;
            case 'thisQuarter':
                $this->startDate = $now->copy()->firstOfQuarter()->format('Y-m-d');
                $this->endDate = $now->format('Y-m-d');
                break;
            case 'lastQuarter':
                $lastQuarter = $now->copy()->subQuarter();
                $this->startDate = $lastQuarter->copy()->firstOfQuarter()->format('Y-m-d');
                $this->endDate = $lastQuarter->copy()->lastOfQuarter()->format('Y-m-d');
                break;
            case 'thisYear':
                $this->startDate = $now->copy()->startOfYear()->format('Y-m-d');
                $this->endDate = $now->format('Y-m-d');
                break;
            case 'lastYear':
                $lastYear = $now->copy()->subYear();
                $this->startDate = $lastYear->copy()->startOfYear()->format('Y-m-d');
                $this->endDate = $lastYear->copy()->endOfYear()->format('Y-m-d');
                break;
        }
    }

    /**
     * Calcule le pourcentage de variation
     */
    protected function calculateVariation($current, $previous): ?float
    {
        if ($previous == 0) {
            return $current > 0 ? 100 : null;
        }
        return round((($current - $previous) / $previous) * 100, 1);
    }

    /**
     * Recupere les stats avec cache
     */
    protected function getCachedStats(string $type, Carbon $start, Carbon $end): array
    {
        $cacheKey = "kpi_custom_{$type}_{$start->format('Y-m-d')}_{$end->format('Y-m-d')}";

        return Cache::remember($cacheKey, $this->cacheTtl, function () use ($type, $start, $end) {
            if ($type === 'compromis') {
                return $this->propertyService->getCompromisStats($start, $end);
            }
            return $this->propertyService->getMandatesExclusStats($start, $end);
        });
    }

    /**
     * Recupere le Top 10 CA Compromis par conseiller
     */
    protected function getTopCompromis(Carbon $start, Carbon $end): array
    {
        $cacheKey = "kpi_custom_top_compromis_{$start->format('Y-m-d')}_{$end->format('Y-m-d')}";

        return Cache::remember($cacheKey, $this->cacheTtl, function () use ($start, $end) {
            return $this->propertyService->getTopCompromisParConseiller($start, $end, 10);
        });
    }

    /**
     * Recupere le Top 10 Mandats Exclusifs par conseiller
     */
    protected function getTopMandats(Carbon $start, Carbon $end): array
    {
        $cacheKey = "kpi_custom_top_mandats_{$start->format('Y-m-d')}_{$end->format('Y-m-d')}";

        return Cache::remember($cacheKey, $this->cacheTtl, function () use ($start, $end) {
            return $this->propertyService->getTopMandatsExclusParConseiller($start, $end, 10);
        });
    }

    /**
     * Calculate same period last year for comparison
     */
    protected function getSamePeriodLastYear(Carbon $start, Carbon $end): array
    {
        return [
            'start' => $start->copy()->subYear(),
            'end' => $end->copy()->subYear(),
        ];
    }

    public function render()
    {
        $this->mongoDbError = false;

        $start = Carbon::parse($this->startDate)->startOfDay();
        $end = Carbon::parse($this->endDate)->endOfDay();
        $lastYear = $this->getSamePeriodLastYear($start, $end);

        // Nombre de jours de la periode
        $daysDiff = $start->diffInDays($end) + 1;

        // Initialisation des donnees
        $compromisData = [
            'current' => ['count' => 0, 'total_price' => 0, 'total_commission' => 0],
            'lastYear' => ['count' => 0, 'total_price' => 0, 'total_commission' => 0],
        ];

        $mandatesData = [
            'current' => ['count' => 0],
            'lastYear' => ['count' => 0],
        ];

        $topCompromis = [];
        $topMandats = [];

        try {
            // C.A Compromis (avec cache)
            $compromisData['current'] = $this->getCachedStats('compromis', $start, $end);
            $compromisData['lastYear'] = $this->getCachedStats('compromis', $lastYear['start'], $lastYear['end']);

            // Mandats Exclusifs (avec cache)
            $mandatesData['current'] = $this->getCachedStats('mandates', $start, $end);
            $mandatesData['lastYear'] = $this->getCachedStats('mandates', $lastYear['start'], $lastYear['end']);

            // Top 10 par conseiller
            $topCompromis = $this->getTopCompromis($start, $end);
            $topMandats = $this->getTopMandats($start, $end);
        } catch (\Exception $e) {
            $this->mongoDbError = true;
            \Log::warning('MongoDB connection error in HebdoBizCustom: ' . $e->getMessage());
        }

        // Calculer CA HT (TTC / 1.20)
        foreach (['current', 'lastYear'] as $period) {
            $compromisData[$period]['total_commission_ht'] = $compromisData[$period]['total_commission'] / 1.20;
        }

        // Calcul des variations (sur le CA HT)
        $variations = [
            'compromis_ca_vs_lastYear' => $this->calculateVariation($compromisData['current']['total_commission_ht'], $compromisData['lastYear']['total_commission_ht']),
            'mandates_vs_lastYear' => $this->calculateVariation($mandatesData['current']['count'], $mandatesData['lastYear']['count']),
        ];

        return view('livewire.kpi.hebdo-biz-custom', [
            'selectedPeriod' => [
                'start' => $start,
                'end' => $end,
            ],
            'lastYearPeriod' => $lastYear,
            'daysDiff' => $daysDiff,
            'compromisData' => $compromisData,
            'mandatesData' => $mandatesData,
            'variations' => $variations,
            'topCompromis' => $topCompromis,
            'topMandats' => $topMandats,
        ]);
    }
}
