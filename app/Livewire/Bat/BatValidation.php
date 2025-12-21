<?php

namespace App\Livewire\Bat;

use App\Models\BatToken;
use App\Models\BatVersion;
use Illuminate\Http\Request;
use Livewire\Component;

class BatValidation extends Component
{
    public ?BatToken $token = null;
    public ?BatVersion $batVersion = null;
    public bool $isValid = false;
    public bool $isExpired = false;
    public bool $isUsed = false;
    public string $comment = '';
    public bool $showConfirmation = false;
    public string $confirmationAction = '';

    public function mount(string $token, Request $request): void
    {
        $this->token = BatToken::with(['batVersion.order.items.supportType'])
            ->where('token', $token)
            ->first();

        if (!$this->token) {
            return;
        }

        $this->batVersion = $this->token->batVersion;
        $this->isExpired = $this->token->isExpired();
        $this->isUsed = !is_null($this->token->used_at);
        $this->isValid = $this->token->isValid() && $this->batVersion->status === 'pending';
    }

    public function confirmAction(string $action): void
    {
        $this->confirmationAction = $action;
        $this->showConfirmation = true;
    }

    public function cancelAction(): void
    {
        $this->showConfirmation = false;
        $this->confirmationAction = '';
    }

    public function validateBat(): void
    {
        $this->processResponse('validated');
    }

    public function refuse(): void
    {
        $this->validateOnly('comment', [
            'comment' => 'required|min:10',
        ], [
            'comment.required' => 'Veuillez expliquer la raison du refus.',
            'comment.min' => 'Le commentaire doit contenir au moins 10 caractères.',
        ]);

        $this->processResponse('refused');
    }

    public function requestModifications(): void
    {
        $this->validateOnly('comment', [
            'comment' => 'required|min:10',
        ], [
            'comment.required' => 'Veuillez décrire les modifications souhaitées.',
            'comment.min' => 'Le commentaire doit contenir au moins 10 caractères.',
        ]);

        $this->processResponse('modifications_requested');
    }

    protected function processResponse(string $status): void
    {
        if (!$this->isValid) {
            return;
        }

        $this->batVersion->update([
            'status' => $status,
            'comment' => $this->comment ?: null,
            'responded_at' => now(),
        ]);

        $this->token->markAsUsed(
            request()->ip(),
            request()->userAgent()
        );

        $orderStatus = match ($status) {
            'validated' => 'validated',
            'refused' => 'refused',
            'modifications_requested' => 'modifications_requested',
            default => null,
        };

        if ($orderStatus) {
            $this->batVersion->order->update(['status' => $orderStatus]);
        }

        $this->isValid = false;
        $this->isUsed = true;
        $this->showConfirmation = false;

        session()->flash('success', match ($status) {
            'validated' => 'BAT validé avec succès. Merci !',
            'refused' => 'BAT refusé. L\'équipe marketing a été notifiée.',
            'modifications_requested' => 'Demande de modifications enregistrée. L\'équipe marketing a été notifiée.',
            default => 'Réponse enregistrée.',
        });
    }

    public function render()
    {
        return view('livewire.bat.bat-validation')
            ->layout('components.layouts.public');
    }
}
