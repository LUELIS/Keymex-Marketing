<?php

namespace App\Livewire\Orders;

use App\Models\Order;
use App\Models\SupportType;
use Livewire\Component;
use Livewire\WithPagination;

class OrderIndex extends Component
{
    use WithPagination;

    public string $search = '';
    public string $status = '';
    public string $supportType = '';
    public string $sortField = 'created_at';
    public string $sortDirection = 'desc';

    protected $queryString = [
        'search' => ['except' => ''],
        'status' => ['except' => ''],
        'supportType' => ['except' => ''],
    ];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingStatus(): void
    {
        $this->resetPage();
    }

    public function sortBy(string $field): void
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function clearFilters(): void
    {
        $this->reset(['search', 'status', 'supportType']);
        $this->resetPage();
    }

    public function render()
    {
        $orders = Order::query()
            ->with(['items.supportType', 'items.format', 'items.category', 'latestBatVersion'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('advisor_name', 'like', "%{$this->search}%")
                      ->orWhere('advisor_email', 'like', "%{$this->search}%")
                      ->orWhere('advisor_agency', 'like', "%{$this->search}%");
                });
            })
            ->when($this->status, fn ($query) => $query->where('status', $this->status))
            ->when($this->supportType, function ($query) {
                $query->whereHas('items', fn ($q) => $q->where('support_type_id', $this->supportType));
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(15);

        return view('livewire.orders.order-index', [
            'orders' => $orders,
            'supportTypes' => SupportType::active()->orderBy('sort_order')->get(),
            'statuses' => [
                'pending' => 'En attente',
                'in_progress' => 'En cours',
                'bat_sent' => 'BAT envoyé',
                'validated' => 'Validé',
                'refused' => 'Refusé',
                'modifications_requested' => 'Modifications',
                'completed' => 'Terminé',
            ],
        ]);
    }
}
