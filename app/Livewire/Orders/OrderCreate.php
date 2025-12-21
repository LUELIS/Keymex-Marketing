<?php

namespace App\Livewire\Orders;

use App\Models\Category;
use App\Models\Order;
use App\Models\SupportType;
use App\Services\MongoAdvisorService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class OrderCreate extends Component
{
    public string $advisorSearch = '';
    public array $advisorResults = [];
    public ?array $selectedAdvisor = null;
    public string $notes = '';
    public array $items = [];
    public bool $showAdvisorDropdown = false;

    protected MongoAdvisorService $advisorService;

    protected $rules = [
        'selectedAdvisor' => 'required|array',
        'selectedAdvisor.id' => 'required|string',
        'items' => 'required|array|min:1',
        'items.*.support_type_id' => 'required|exists:support_types,id',
        'items.*.format_id' => 'nullable|exists:formats,id',
        'items.*.category_id' => 'nullable|exists:categories,id',
        'items.*.quantity' => 'required|integer|min:1|max:9999',
    ];

    protected $messages = [
        'selectedAdvisor.required' => 'Veuillez sélectionner un conseiller.',
        'items.required' => 'Veuillez ajouter au moins un article.',
        'items.min' => 'Veuillez ajouter au moins un article.',
        'items.*.support_type_id.required' => 'Le type de support est requis.',
        'items.*.quantity.required' => 'La quantité est requise.',
        'items.*.quantity.min' => 'La quantité minimum est 1.',
    ];

    public function boot(MongoAdvisorService $advisorService): void
    {
        $this->advisorService = $advisorService;
    }

    public function mount(): void
    {
        $this->addItem();
    }

    public function updatedAdvisorSearch(): void
    {
        if (strlen($this->advisorSearch) >= 2) {
            $this->advisorResults = $this->advisorService->search($this->advisorSearch, 10)->toArray();
            $this->showAdvisorDropdown = true;
        } else {
            $this->advisorResults = [];
            $this->showAdvisorDropdown = false;
        }
    }

    public function selectAdvisor(int $index): void
    {
        if (isset($this->advisorResults[$index])) {
            $this->selectedAdvisor = $this->advisorResults[$index];
            $this->advisorSearch = $this->selectedAdvisor['fullname'];
            $this->showAdvisorDropdown = false;
            $this->advisorResults = [];
        }
    }

    public function clearAdvisor(): void
    {
        $this->selectedAdvisor = null;
        $this->advisorSearch = '';
        $this->advisorResults = [];
    }

    public function addItem(): void
    {
        $this->items[] = [
            'support_type_id' => '',
            'format_id' => '',
            'category_id' => '',
            'quantity' => 1,
            'notes' => '',
            'available_formats' => [],
        ];
    }

    public function removeItem(int $index): void
    {
        if (count($this->items) > 1) {
            unset($this->items[$index]);
            $this->items = array_values($this->items);
        }
    }

    public function updatedItems($value, $key): void
    {
        if (str_contains($key, 'support_type_id')) {
            $index = (int) explode('.', $key)[0];
            $this->loadFormatsForItem($index);
        }
    }

    protected function loadFormatsForItem(int $index): void
    {
        $supportTypeId = $this->items[$index]['support_type_id'] ?? null;

        if ($supportTypeId) {
            $supportType = SupportType::with('formats')->find($supportTypeId);
            $this->items[$index]['available_formats'] = $supportType?->formats->toArray() ?? [];
            $this->items[$index]['format_id'] = '';
        } else {
            $this->items[$index]['available_formats'] = [];
            $this->items[$index]['format_id'] = '';
        }
    }

    public function save(): void
    {
        $this->validate();

        $order = Order::create([
            'advisor_mongo_id' => $this->selectedAdvisor['id'],
            'advisor_name' => $this->selectedAdvisor['fullname'],
            'advisor_email' => $this->selectedAdvisor['email'],
            'advisor_agency' => $this->selectedAdvisor['agency'] ?? '',
            'status' => 'pending',
            'notes' => $this->notes,
            'created_by' => Auth::id(),
        ]);

        foreach ($this->items as $item) {
            $order->items()->create([
                'support_type_id' => $item['support_type_id'],
                'format_id' => $item['format_id'] ?: null,
                'category_id' => $item['category_id'] ?: null,
                'quantity' => $item['quantity'],
                'notes' => $item['notes'] ?? '',
            ]);
        }

        session()->flash('success', 'Commande créée avec succès.');
        $this->redirect(route('orders.show', $order), navigate: true);
    }

    public function render()
    {
        return view('livewire.orders.order-create', [
            'supportTypes' => SupportType::active()->with('formats')->orderBy('sort_order')->get(),
            'categories' => Category::active()->orderBy('sort_order')->get(),
        ]);
    }
}
