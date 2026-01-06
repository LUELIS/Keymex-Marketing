<?php

namespace App\Livewire\Kpi;

use App\Services\MongoPropertyService;
use Carbon\Carbon;
use Livewire\Component;

class KeyPerformeurs extends Component
{
    public string $filterType = 'year'; // year, semester, quarter, custom
    public int $selectedYear;
    public int $selectedSemester = 1;
    public int $selectedQuarter = 1;
    public ?string $customStartDate = null;
    public ?string $customEndDate = null;

    public array $conseillers = [];
    public array $stats = [];
    public array $periodInfo = [];

    protected MongoPropertyService $mongoService;

    public function boot(MongoPropertyService $mongoService): void
    {
        $this->mongoService = $mongoService;
    }

    public function mount(): void
    {
        $this->selectedYear = now()->year;
        $this->selectedSemester = now()->month <= 6 ? 1 : 2;
        $this->selectedQuarter = ceil(now()->month / 3);
        $this->loadData();
    }

    public function updatedFilterType(): void
    {
        $this->loadData();
    }

    public function updatedSelectedYear(): void
    {
        $this->loadData();
    }

    public function updatedSelectedSemester(): void
    {
        $this->loadData();
    }

    public function updatedSelectedQuarter(): void
    {
        $this->loadData();
    }

    public function applyCustomDates(): void
    {
        if ($this->customStartDate && $this->customEndDate) {
            $this->filterType = 'custom';
            $this->loadData();
        }
    }

    public function previousPeriod(): void
    {
        match ($this->filterType) {
            'year' => $this->selectedYear--,
            'semester' => $this->moveSemester(-1),
            'quarter' => $this->moveQuarter(-1),
            default => null,
        };
        $this->loadData();
    }

    public function nextPeriod(): void
    {
        match ($this->filterType) {
            'year' => $this->selectedYear++,
            'semester' => $this->moveSemester(1),
            'quarter' => $this->moveQuarter(1),
            default => null,
        };
        $this->loadData();
    }

    protected function moveSemester(int $direction): void
    {
        $this->selectedSemester += $direction;
        if ($this->selectedSemester > 2) {
            $this->selectedSemester = 1;
            $this->selectedYear++;
        } elseif ($this->selectedSemester < 1) {
            $this->selectedSemester = 2;
            $this->selectedYear--;
        }
    }

    protected function moveQuarter(int $direction): void
    {
        $this->selectedQuarter += $direction;
        if ($this->selectedQuarter > 4) {
            $this->selectedQuarter = 1;
            $this->selectedYear++;
        } elseif ($this->selectedQuarter < 1) {
            $this->selectedQuarter = 4;
            $this->selectedYear--;
        }
    }

    public function canGoNext(): bool
    {
        $now = now();
        return match ($this->filterType) {
            'year' => $this->selectedYear < $now->year,
            'semester' => $this->selectedYear < $now->year || ($this->selectedYear === $now->year && $this->selectedSemester < ($now->month <= 6 ? 1 : 2)),
            'quarter' => $this->selectedYear < $now->year || ($this->selectedYear === $now->year && $this->selectedQuarter < ceil($now->month / 3)),
            default => false,
        };
    }

    protected function loadData(): void
    {
        [$startDate, $endDate] = $this->getPeriodDates();

        $this->periodInfo = [
            'start' => $startDate,
            'end' => $endDate,
            'label' => $this->getPeriodLabel(),
        ];

        $this->conseillers = $this->mongoService->getAllConseillersCA($startDate, $endDate);
        $this->stats = $this->calculateStats();
    }

    protected function getPeriodDates(): array
    {
        return match ($this->filterType) {
            'year' => [
                Carbon::create($this->selectedYear, 1, 1)->startOfDay(),
                Carbon::create($this->selectedYear, 12, 31)->endOfDay(),
            ],
            'semester' => [
                Carbon::create($this->selectedYear, $this->selectedSemester === 1 ? 1 : 7, 1)->startOfDay(),
                Carbon::create($this->selectedYear, $this->selectedSemester === 1 ? 6 : 12, 1)->endOfMonth()->endOfDay(),
            ],
            'quarter' => [
                Carbon::create($this->selectedYear, ($this->selectedQuarter - 1) * 3 + 1, 1)->startOfDay(),
                Carbon::create($this->selectedYear, $this->selectedQuarter * 3, 1)->endOfMonth()->endOfDay(),
            ],
            'custom' => [
                Carbon::parse($this->customStartDate)->startOfDay(),
                Carbon::parse($this->customEndDate)->endOfDay(),
            ],
            default => [
                Carbon::create($this->selectedYear, 1, 1)->startOfDay(),
                Carbon::create($this->selectedYear, 12, 31)->endOfDay(),
            ],
        };
    }

    protected function getPeriodLabel(): string
    {
        return match ($this->filterType) {
            'year' => "Année {$this->selectedYear}",
            'semester' => "S{$this->selectedSemester} {$this->selectedYear}",
            'quarter' => "T{$this->selectedQuarter} {$this->selectedYear}",
            'custom' => Carbon::parse($this->customStartDate)->format('d/m/Y') . ' - ' . Carbon::parse($this->customEndDate)->format('d/m/Y'),
            default => "Année {$this->selectedYear}",
        };
    }

    protected function calculateStats(): array
    {
        $stats = [
            'elite' => ['count' => 0, 'total_ca' => 0],
            'platine' => ['count' => 0, 'total_ca' => 0],
            'or' => ['count' => 0, 'total_ca' => 0],
            'argent' => ['count' => 0, 'total_ca' => 0],
            'bronze' => ['count' => 0, 'total_ca' => 0],
            'non_classe' => ['count' => 0, 'total_ca' => 0],
        ];

        foreach ($this->conseillers as $conseiller) {
            $category = $conseiller['category'];
            $stats[$category]['count']++;
            $stats[$category]['total_ca'] += $conseiller['ca'];
        }

        return $stats;
    }

    /**
     * Détermine la catégorie en fonction du CA (en euros)
     */
    public static function getCategory(float $ca): string
    {
        $caK = $ca / 1000;

        if ($caK >= 300) {
            return 'elite';
        } elseif ($caK >= 200) {
            return 'platine';
        } elseif ($caK >= 150) {
            return 'or';
        } elseif ($caK >= 100) {
            return 'argent';
        } elseif ($caK >= 50) {
            return 'bronze';
        }

        return 'non_classe';
    }

    /**
     * Retourne les infos de la catégorie
     */
    public static function getCategoryInfo(string $category): array
    {
        return match ($category) {
            'elite' => [
                'label' => 'Elite',
                'icon' => '👑',
                'min' => '300K€+',
                'color' => 'purple',
            ],
            'platine' => [
                'label' => 'Platine',
                'icon' => '💎',
                'min' => '200-300K€',
                'color' => 'slate',
            ],
            'or' => [
                'label' => 'Or',
                'icon' => '🥇',
                'min' => '150-200K€',
                'color' => 'yellow',
            ],
            'argent' => [
                'label' => 'Argent',
                'icon' => '🥈',
                'min' => '100-150K€',
                'color' => 'gray',
            ],
            'bronze' => [
                'label' => 'Bronze',
                'icon' => '🥉',
                'min' => '50-100K€',
                'color' => 'orange',
            ],
            default => [
                'label' => 'En progression',
                'icon' => '🚀',
                'min' => '< 50K€',
                'color' => 'gray',
            ],
        };
    }

    public function render()
    {
        return view('livewire.kpi.key-performeurs');
    }
}
