<?php

namespace App\Livewire\Stats;

use App\Models\BatVersion;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\StandaloneBat;
use App\Models\SupportType;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Dashboard extends Component
{
    public string $period = 'month';
    public ?string $dateFrom = null;
    public ?string $dateTo = null;

    protected $queryString = [
        'period' => ['except' => 'month'],
        'dateFrom' => ['except' => null],
        'dateTo' => ['except' => null],
    ];

    public function mount(): void
    {
        // Initialiser les dates si pas definies
        if (!$this->dateFrom && !$this->dateTo && $this->period === 'month') {
            $this->applyPeriod();
        }
    }

    public function updatedPeriod(): void
    {
        if ($this->period !== 'custom') {
            $this->applyPeriod();
        }
    }

    public function applyPeriod(): void
    {
        $now = Carbon::now();

        [$start, $end] = match ($this->period) {
            'today' => [$now->copy()->startOfDay(), $now->copy()->endOfDay()],
            'yesterday' => [$now->copy()->subDay()->startOfDay(), $now->copy()->subDay()->endOfDay()],
            'week' => [$now->copy()->startOfWeek(), $now->copy()->endOfWeek()],
            'last_week' => [$now->copy()->subWeek()->startOfWeek(), $now->copy()->subWeek()->endOfWeek()],
            'month' => [$now->copy()->startOfMonth(), $now->copy()->endOfMonth()],
            'last_month' => [$now->copy()->subMonth()->startOfMonth(), $now->copy()->subMonth()->endOfMonth()],
            'quarter' => [$now->copy()->startOfQuarter(), $now->copy()->endOfQuarter()],
            'year' => [$now->copy()->startOfYear(), $now->copy()->endOfYear()],
            'last_year' => [$now->copy()->subYear()->startOfYear(), $now->copy()->subYear()->endOfYear()],
            default => [$now->copy()->startOfMonth(), $now->copy()->endOfMonth()],
        };

        $this->dateFrom = $start->format('Y-m-d');
        $this->dateTo = $end->format('Y-m-d');
    }

    public function applyCustomDates(): void
    {
        $this->period = 'custom';
    }

    protected function getDateRange(): array
    {
        $startDate = $this->dateFrom
            ? Carbon::parse($this->dateFrom)->startOfDay()
            : Carbon::now()->startOfMonth();

        $endDate = $this->dateTo
            ? Carbon::parse($this->dateTo)->endOfDay()
            : Carbon::now()->endOfDay();

        return [$startDate, $endDate];
    }

    public function render()
    {
        [$startDate, $endDate] = $this->getDateRange();

        // === STATISTIQUES PRINCIPALES ===

        // Commandes dans la periode
        $totalOrders = Order::whereBetween('created_at', [$startDate, $endDate])->count();
        $ordersCompleted = Order::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'completed')
            ->count();

        // Total BAT envoyes
        $totalBatsSent = StandaloneBat::whereBetween('sent_at', [$startDate, $endDate])->count();

        // BAT valides (BatVersion + StandaloneBat valides/convertis)
        $batVersionsValidated = BatVersion::whereBetween('responded_at', [$startDate, $endDate])
            ->where('status', 'validated')
            ->count();
        $standaloneBatsValidated = StandaloneBat::whereBetween('responded_at', [$startDate, $endDate])
            ->whereIn('status', ['validated', 'converted'])
            ->count();
        $batsValidated = $batVersionsValidated + $standaloneBatsValidated;

        // BAT refuses/modifications (pour le taux)
        $batsRefused = BatVersion::whereBetween('responded_at', [$startDate, $endDate])->where('status', 'refused')->count()
            + StandaloneBat::whereBetween('responded_at', [$startDate, $endDate])->where('status', 'refused')->count();
        $batsModifications = BatVersion::whereBetween('responded_at', [$startDate, $endDate])->where('status', 'modifications_requested')->count()
            + StandaloneBat::whereBetween('responded_at', [$startDate, $endDate])->where('status', 'modifications_requested')->count();

        // Taux de validation
        $batsWithResponse = $batsValidated + $batsRefused + $batsModifications;
        $validationRate = $batsWithResponse > 0 ? round(($batsValidated / $batsWithResponse) * 100) : 0;
        $refusalRate = $batsWithResponse > 0 ? round(($batsRefused / $batsWithResponse) * 100) : 0;
        $modificationsRate = $batsWithResponse > 0 ? round(($batsModifications / $batsWithResponse) * 100) : 0;

        // BAT en attente (tous types, independant de la periode)
        $pendingBatVersions = BatVersion::where('status', 'pending')
            ->whereHas('activeToken')
            ->count();
        $pendingStandaloneBats = StandaloneBat::whereIn('status', ['pending', 'sent'])
            ->where('token_expires_at', '>', now())
            ->whereNull('token_used_at')
            ->count();
        $batsPending = $pendingBatVersions + $pendingStandaloneBats;

        // === TABLEAU CROISE TYPE DE SUPPORT x CATEGORIE ===

        // Recuperer les types de support et categories actifs
        $supportTypes = SupportType::active()->orderBy('sort_order')->get();
        $categories = Category::active()->orderBy('sort_order')->get();

        // Stats par type de support (OrderItem)
        $orderItemsBySupportType = OrderItem::select('support_type_id', DB::raw('COUNT(*) as count'), DB::raw('SUM(quantity) as total_quantity'))
            ->whereHas('order', function ($q) use ($startDate, $endDate) {
                $q->whereBetween('created_at', [$startDate, $endDate]);
            })
            ->groupBy('support_type_id')
            ->pluck('count', 'support_type_id')
            ->toArray();

        $orderItemQuantityBySupportType = OrderItem::select('support_type_id', DB::raw('SUM(quantity) as total_quantity'))
            ->whereHas('order', function ($q) use ($startDate, $endDate) {
                $q->whereBetween('created_at', [$startDate, $endDate]);
            })
            ->groupBy('support_type_id')
            ->pluck('total_quantity', 'support_type_id')
            ->toArray();

        // Stats par categorie (OrderItem)
        $orderItemsByCategory = OrderItem::select('category_id', DB::raw('COUNT(*) as count'))
            ->whereHas('order', function ($q) use ($startDate, $endDate) {
                $q->whereBetween('created_at', [$startDate, $endDate]);
            })
            ->whereNotNull('category_id')
            ->groupBy('category_id')
            ->pluck('count', 'category_id')
            ->toArray();

        // BAT par type de support
        $batsBySupportType = StandaloneBat::select('support_type_id', DB::raw('COUNT(*) as count'))
            ->whereBetween('created_at', [$startDate, $endDate])
            ->whereNotNull('support_type_id')
            ->groupBy('support_type_id')
            ->pluck('count', 'support_type_id')
            ->toArray();

        // BAT par categorie
        $batsByCategory = StandaloneBat::select('category_id', DB::raw('COUNT(*) as count'))
            ->whereBetween('created_at', [$startDate, $endDate])
            ->whereNotNull('category_id')
            ->groupBy('category_id')
            ->pluck('count', 'category_id')
            ->toArray();

        // Creer le tableau croise
        $supportTypeStats = [];
        foreach ($supportTypes as $type) {
            $supportTypeStats[] = [
                'id' => $type->id,
                'name' => $type->name,
                'orders_count' => $orderItemsBySupportType[$type->id] ?? 0,
                'orders_quantity' => $orderItemQuantityBySupportType[$type->id] ?? 0,
                'bats_count' => $batsBySupportType[$type->id] ?? 0,
            ];
        }

        $categoryStats = [];
        foreach ($categories as $cat) {
            $categoryStats[] = [
                'id' => $cat->id,
                'name' => $cat->name,
                'orders_count' => $orderItemsByCategory[$cat->id] ?? 0,
                'bats_count' => $batsByCategory[$cat->id] ?? 0,
            ];
        }

        // === STATISTIQUES PAR STATUT ===
        $ordersByStatus = Order::whereBetween('created_at', [$startDate, $endDate])
            ->select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        $batsByStatus = StandaloneBat::whereBetween('created_at', [$startDate, $endDate])
            ->select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        return view('livewire.stats.dashboard', [
            'totalOrders' => $totalOrders,
            'ordersCompleted' => $ordersCompleted,
            'totalBatsSent' => $totalBatsSent,
            'batsValidated' => $batsValidated,
            'batsRefused' => $batsRefused,
            'batsModifications' => $batsModifications,
            'batsPending' => $batsPending,
            'validationRate' => $validationRate,
            'refusalRate' => $refusalRate,
            'modificationsRate' => $modificationsRate,
            'supportTypeStats' => $supportTypeStats,
            'categoryStats' => $categoryStats,
            'ordersByStatus' => $ordersByStatus,
            'batsByStatus' => $batsByStatus,
        ]);
    }
}
