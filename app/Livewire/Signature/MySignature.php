<?php

namespace App\Livewire\Signature;

use App\Models\SignatureTemplate;
use App\Services\MongoPropertyService;
use App\Services\SignatureGeneratorService;
use Livewire\Component;

class MySignature extends Component
{
    public bool $isAuthenticated = false;
    public ?string $userEmail = null;
    public ?string $userName = null;
    public ?array $advisor = null;
    public ?string $signatureHtml = null;
    public ?string $error = null;
    public bool $advisorNotFound = false;

    protected MongoPropertyService $mongoService;
    protected SignatureGeneratorService $signatureService;

    public function boot(MongoPropertyService $mongoService, SignatureGeneratorService $signatureService): void
    {
        $this->mongoService = $mongoService;
        $this->signatureService = $signatureService;
    }

    public function mount(): void
    {
        // Verifier si l'utilisateur est authentifie via la session
        $this->userEmail = session('signature_user_email');
        $this->userName = session('signature_user_name');
        $this->isAuthenticated = !empty($this->userEmail);

        if ($this->isAuthenticated) {
            $this->loadAdvisorAndGenerateSignature();
        }
    }

    protected function loadAdvisorAndGenerateSignature(): void
    {
        try {
            // Rechercher le conseiller dans MongoDB par email
            $this->advisor = $this->mongoService->getAdvisorByEmail($this->userEmail);

            if (!$this->advisor) {
                $this->advisorNotFound = true;
                $this->error = "Aucun conseiller trouve avec l'email {$this->userEmail}";
                return;
            }

            // Generer la signature
            $this->generateSignature();

        } catch (\Exception $e) {
            $this->error = 'Erreur lors du chargement des donnees: ' . $e->getMessage();
            report($e);
        }
    }

    protected function generateSignature(): void
    {
        if (!$this->advisor) {
            return;
        }

        try {
            // Utiliser le template par defaut
            $this->signatureHtml = $this->signatureService->generateDefault($this->advisor, false);

            if (!$this->signatureHtml) {
                $this->error = 'Aucun template de signature configure. Contactez un administrateur.';
            }

        } catch (\Exception $e) {
            $this->error = 'Erreur lors de la generation de la signature: ' . $e->getMessage();
            report($e);
        }
    }

    public function logout(): void
    {
        session()->forget([
            'signature_user_email',
            'signature_user_name',
            'signature_authenticated_at',
        ]);

        $this->isAuthenticated = false;
        $this->userEmail = null;
        $this->userName = null;
        $this->advisor = null;
        $this->signatureHtml = null;
        $this->error = null;
        $this->advisorNotFound = false;
    }

    public function render()
    {
        return view('livewire.signature.my-signature')
            ->layout('components.layouts.public', ['title' => 'Ma Signature KEYMEX']);
    }
}
