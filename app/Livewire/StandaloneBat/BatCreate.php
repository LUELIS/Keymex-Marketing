<?php

namespace App\Livewire\StandaloneBat;

use App\Models\StandaloneBat;
use App\Services\MongoAdvisorService;
use Livewire\Component;
use Livewire\WithFileUploads;

class BatCreate extends Component
{
    use WithFileUploads;

    // Advisor
    public string $advisorSearch = '';
    public array $advisorResults = [];
    public ?array $selectedAdvisor = null;
    public bool $showAdvisorDropdown = false;

    // BAT details
    public string $title = '';
    public string $description = '';
    public $batFile;

    protected MongoAdvisorService $advisorService;

    public function boot(MongoAdvisorService $advisorService): void
    {
        $this->advisorService = $advisorService;
    }

    public function updatedAdvisorSearch(): void
    {
        if (strlen($this->advisorSearch) >= 2) {
            try {
                $this->advisorResults = $this->advisorService->search($this->advisorSearch, 10)->toArray();
                $this->showAdvisorDropdown = true;
            } catch (\Exception $e) {
                $this->advisorResults = [];
            }
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

    public function save()
    {
        $this->validate([
            'batFile' => 'required|file|mimes:pdf,jpg,jpeg,png|max:20480',
        ], [
            'batFile.required' => 'Le fichier BAT est obligatoire.',
            'batFile.mimes' => 'Le fichier doit etre un PDF ou une image (JPG, PNG).',
            'batFile.max' => 'Le fichier ne doit pas depasser 20 Mo.',
        ]);

        if (!$this->selectedAdvisor) {
            $this->addError('advisorSearch', 'Veuillez selectionner un conseiller.');
            return;
        }

        // Store file
        $fileName = 'bat_' . time() . '_' . uniqid() . '.' . $this->batFile->extension();
        $filePath = $this->batFile->storeAs('standalone-bats', $fileName, 'public');

        // Create BAT
        StandaloneBat::create([
            'advisor_mongo_id' => $this->selectedAdvisor['id'],
            'advisor_name' => $this->selectedAdvisor['fullname'],
            'advisor_email' => $this->selectedAdvisor['email'],
            'advisor_agency' => $this->selectedAdvisor['agency'] ?? null,
            'file_path' => $filePath,
            'file_name' => $this->batFile->getClientOriginalName(),
            'file_mime' => $this->batFile->getMimeType(),
            'title' => $this->title ?: null,
            'description' => $this->description ?: null,
            'status' => 'draft',
            'created_by' => auth()->id(),
        ]);

        session()->flash('success', 'BAT cree avec succes. Vous pouvez maintenant l\'envoyer pour validation.');

        return $this->redirect(route('standalone-bats.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.standalone-bat.bat-create');
    }
}
