<?php

namespace App\Livewire\Orders;

use App\Models\BatVersion;
use App\Models\Category;
use App\Models\Format;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\SupportType;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class OrderShow extends Component
{
    use WithFileUploads;

    public Order $order;
    public bool $showUploadModal = false;
    public bool $showStatusModal = false;
    public bool $showTrackingModal = false;
    public bool $showEditItemModal = false;
    public bool $showEditOrderModal = false;
    public $batFile;
    public string $newStatus = '';
    public string $statusComment = '';
    public ?string $trackingUrl = null;

    // Edit item properties
    public ?int $editingItemId = null;
    public ?int $editItemSupportTypeId = null;
    public ?int $editItemFormatId = null;
    public ?int $editItemCategoryId = null;
    public int $editItemQuantity = 1;
    public string $editItemNotes = '';

    // Edit order properties
    public string $editAdvisorName = '';
    public string $editAdvisorEmail = '';
    public string $editAdvisorAgency = '';
    public string $editOrderNotes = '';

    protected $listeners = ['refreshOrder' => '$refresh'];

    public function mount(Order $order): void
    {
        $this->order = $order->load([
            'items.supportType',
            'items.format',
            'items.category',
            'batVersions.tokens',
            'batVersions.sentBy',
            'creator',
            'standaloneBat.logs',
        ]);
        $this->trackingUrl = $order->tracking_url;
    }

    public function uploadBat(): void
    {
        $this->validate([
            'batFile' => 'required|file|mimes:pdf,jpg,jpeg,png|max:20480',
        ], [
            'batFile.required' => 'Veuillez selectionner un fichier.',
            'batFile.mimes' => 'Le fichier doit etre un PDF ou une image (JPG, PNG).',
            'batFile.max' => 'Le fichier ne doit pas depasser 20 Mo.',
        ]);

        $versionNumber = $this->order->batVersions->count() + 1;
        $fileName = "bat_v{$versionNumber}_{$this->order->id}." . $this->batFile->extension();
        $filePath = $this->batFile->storeAs("bats/{$this->order->id}", $fileName, 'public');

        $batVersion = $this->order->batVersions()->create([
            'version_number' => $versionNumber,
            'file_path' => $filePath,
            'file_name' => $this->batFile->getClientOriginalName(),
            'file_mime' => $this->batFile->getMimeType(),
            'status' => 'pending',
            'sent_at' => now(),
            'sent_by' => Auth::id(),
        ]);

        $batVersion->createToken(30);

        if ($this->order->status === 'pending' || $this->order->status === 'in_progress') {
            $this->order->update(['status' => 'bat_sent']);
        }

        $this->reset(['batFile', 'showUploadModal']);
        $this->order->refresh();

        session()->flash('success', "BAT v{$versionNumber} envoye avec succes.");
    }

    public function openStatusModal(): void
    {
        $this->showStatusModal = true;
        $this->newStatus = $this->order->status;
    }

    public function updateOrderStatus(): void
    {
        $this->validate([
            'newStatus' => 'required|in:pending,in_progress,bat_sent,validated,refused,modifications_requested,completed',
        ]);

        $this->order->update(['status' => $this->newStatus]);
        $this->showStatusModal = false;
        $this->order->refresh();

        session()->flash('success', 'Statut mis a jour avec succes.');
    }

    public function openTrackingModal(): void
    {
        $this->trackingUrl = $this->order->tracking_url;
        $this->showTrackingModal = true;
    }

    public function updateTrackingUrl(): void
    {
        $this->validate([
            'trackingUrl' => 'nullable|url|max:500',
        ], [
            'trackingUrl.url' => 'Veuillez entrer une URL valide.',
        ]);

        $this->order->update(['tracking_url' => $this->trackingUrl ?: null]);
        $this->showTrackingModal = false;
        $this->order->refresh();

        session()->flash('success', 'Lien de suivi mis a jour.');
    }

    public function copyValidationLink(int $batVersionId): void
    {
        $batVersion = BatVersion::with('activeToken')->find($batVersionId);

        if ($batVersion && $batVersion->activeToken) {
            $this->dispatch('copy-to-clipboard', url: route('bat.validation', $batVersion->activeToken->token));
        }
    }

    public function regenerateToken(int $batVersionId): void
    {
        $batVersion = BatVersion::find($batVersionId);

        if ($batVersion) {
            $batVersion->tokens()->whereNull('used_at')->update(['expires_at' => now()]);
            $batVersion->createToken(30);
            $this->order->refresh();

            session()->flash('success', 'Nouveau lien de validation genere.');
        }
    }

    public function deleteBatVersion(int $batVersionId): void
    {
        $batVersion = BatVersion::find($batVersionId);

        if ($batVersion && $batVersion->order_id === $this->order->id) {
            if ($batVersion->file_path) {
                Storage::disk('public')->delete($batVersion->file_path);
            }
            $batVersion->tokens()->delete();
            $batVersion->delete();
            $this->order->refresh();

            session()->flash('success', 'Version BAT supprimee.');
        }
    }

    // Edit Item Methods
    public function openEditItemModal(int $itemId): void
    {
        $item = OrderItem::find($itemId);

        if ($item && $item->order_id === $this->order->id) {
            $this->editingItemId = $itemId;
            $this->editItemSupportTypeId = $item->support_type_id;
            $this->editItemFormatId = $item->format_id;
            $this->editItemCategoryId = $item->category_id;
            $this->editItemQuantity = $item->quantity;
            $this->editItemNotes = $item->notes ?? '';
            $this->showEditItemModal = true;
        }
    }

    public function closeEditItemModal(): void
    {
        $this->showEditItemModal = false;
        $this->editingItemId = null;
        $this->editItemSupportTypeId = null;
        $this->editItemFormatId = null;
        $this->editItemCategoryId = null;
        $this->editItemQuantity = 1;
        $this->editItemNotes = '';
    }

    public function updateItem(): void
    {
        $this->validate([
            'editItemSupportTypeId' => 'required|exists:support_types,id',
            'editItemFormatId' => 'nullable|exists:formats,id',
            'editItemCategoryId' => 'nullable|exists:categories,id',
            'editItemQuantity' => 'required|integer|min:1',
            'editItemNotes' => 'nullable|string|max:500',
        ], [
            'editItemSupportTypeId.required' => 'Le type de support est obligatoire.',
            'editItemQuantity.required' => 'La quantite est obligatoire.',
            'editItemQuantity.min' => 'La quantite doit etre au moins 1.',
        ]);

        $item = OrderItem::find($this->editingItemId);

        if ($item && $item->order_id === $this->order->id) {
            $item->update([
                'support_type_id' => $this->editItemSupportTypeId,
                'format_id' => $this->editItemFormatId,
                'category_id' => $this->editItemCategoryId,
                'quantity' => $this->editItemQuantity,
                'notes' => $this->editItemNotes ?: null,
            ]);

            $this->closeEditItemModal();
            $this->order->refresh();
            $this->order->load(['items.supportType', 'items.format', 'items.category']);

            session()->flash('success', 'Article mis a jour avec succes.');
        }
    }

    public function deleteItem(int $itemId): void
    {
        $item = OrderItem::find($itemId);

        if ($item && $item->order_id === $this->order->id) {
            // Ne pas supprimer si c'est le dernier article
            if ($this->order->items->count() <= 1) {
                session()->flash('error', 'Impossible de supprimer le dernier article de la commande.');
                return;
            }

            $item->delete();
            $this->order->refresh();
            $this->order->load(['items.supportType', 'items.format', 'items.category']);

            session()->flash('success', 'Article supprime.');
        }
    }

    // Edit Order Methods
    public function openEditOrderModal(): void
    {
        $this->editAdvisorName = $this->order->advisor_name;
        $this->editAdvisorEmail = $this->order->advisor_email;
        $this->editAdvisorAgency = $this->order->advisor_agency ?? '';
        $this->editOrderNotes = $this->order->notes ?? '';
        $this->showEditOrderModal = true;
    }

    public function closeEditOrderModal(): void
    {
        $this->showEditOrderModal = false;
        $this->editAdvisorName = '';
        $this->editAdvisorEmail = '';
        $this->editAdvisorAgency = '';
        $this->editOrderNotes = '';
    }

    public function updateOrder(): void
    {
        $this->validate([
            'editAdvisorName' => 'required|string|max:255',
            'editAdvisorEmail' => 'required|email|max:255',
            'editAdvisorAgency' => 'nullable|string|max:255',
            'editOrderNotes' => 'nullable|string|max:1000',
        ], [
            'editAdvisorName.required' => 'Le nom du conseiller est obligatoire.',
            'editAdvisorEmail.required' => 'L\'email du conseiller est obligatoire.',
            'editAdvisorEmail.email' => 'L\'email doit etre valide.',
        ]);

        $this->order->update([
            'advisor_name' => $this->editAdvisorName,
            'advisor_email' => $this->editAdvisorEmail,
            'advisor_agency' => $this->editAdvisorAgency ?: null,
            'notes' => $this->editOrderNotes ?: null,
        ]);

        $this->closeEditOrderModal();
        $this->order->refresh();

        session()->flash('success', 'Commande mise a jour avec succes.');
    }

    public function render()
    {
        return view('livewire.orders.order-show', [
            'statuses' => [
                'pending' => 'En attente',
                'in_progress' => 'En cours',
                'bat_sent' => 'BAT envoye',
                'validated' => 'Valide',
                'refused' => 'Refuse',
                'modifications_requested' => 'Modifications',
                'completed' => 'Termine',
            ],
            'supportTypes' => SupportType::active()->orderBy('sort_order')->get(),
            'formats' => Format::active()->orderBy('sort_order')->get(),
            'categories' => Category::active()->orderBy('sort_order')->get(),
        ]);
    }
}
