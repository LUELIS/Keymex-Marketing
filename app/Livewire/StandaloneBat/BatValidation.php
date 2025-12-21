<?php

namespace App\Livewire\StandaloneBat;

use App\Models\StandaloneBat;
use Livewire\Component;

class BatValidation extends Component
{
    public StandaloneBat $bat;
    public string $token;
    public bool $tokenValid = false;
    public bool $alreadyResponded = false;

    public string $action = '';
    public string $comment = '';
    public bool $showConfirmModal = false;

    public function mount(string $token): void
    {
        $this->token = $token;

        $bat = StandaloneBat::where('validation_token', $token)->first();

        if (!$bat) {
            abort(404);
        }

        $this->bat = $bat;

        // Check if already responded
        if (in_array($bat->status, ['validated', 'refused', 'modifications_requested', 'converted'])) {
            $this->alreadyResponded = true;
            return;
        }

        // Check token validity
        $this->tokenValid = $bat->isTokenValid();
    }

    public function openConfirm(string $action): void
    {
        $this->action = $action;
        $this->showConfirmModal = true;
    }

    public function closeConfirm(): void
    {
        $this->showConfirmModal = false;
        $this->action = '';
        $this->comment = '';
    }

    public function confirm(): void
    {
        if (!$this->tokenValid || $this->alreadyResponded) {
            return;
        }

        match ($this->action) {
            'validate' => $this->bat->validate($this->comment ?: null),
            'refuse' => $this->bat->refuse($this->comment ?: null),
            'modifications' => $this->bat->requestModifications($this->comment ?: null),
            default => null,
        };

        $this->alreadyResponded = true;
        $this->closeConfirm();
    }

    public function render()
    {
        return view('livewire.standalone-bat.bat-validation')
            ->layout('components.layouts.public');
    }
}
