<?php

namespace App\Livewire\Settings;

use App\Models\StorageSetting;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class StorageSettings extends Component
{
    public string $driver = 'local';
    public string $s3_key = '';
    public string $s3_secret = '';
    public string $s3_region = 'fr-par';
    public string $s3_bucket = '';
    public string $s3_endpoint = 'https://s3.fr-par.scw.cloud';
    public string $s3_url = '';
    public bool $s3_path_style = true;
    public bool $is_active = false;

    public bool $showSecret = false;
    public bool $testing = false;

    protected function rules(): array
    {
        return [
            'driver' => 'required|in:local,s3',
            's3_key' => 'required_if:driver,s3|nullable|string|max:255',
            's3_secret' => 'nullable|string|max:255',
            's3_region' => 'required_if:driver,s3|nullable|string|max:50',
            's3_bucket' => 'required_if:driver,s3|nullable|string|max:255',
            's3_endpoint' => 'required_if:driver,s3|nullable|url|max:255',
            's3_url' => 'nullable|url|max:255',
            's3_path_style' => 'boolean',
        ];
    }

    protected function messages(): array
    {
        return [
            's3_key.required_if' => 'L\'Access Key est obligatoire pour S3.',
            's3_region.required_if' => 'La region est obligatoire pour S3.',
            's3_bucket.required_if' => 'Le bucket est obligatoire pour S3.',
            's3_endpoint.required_if' => 'L\'endpoint est obligatoire pour S3.',
            's3_endpoint.url' => 'L\'endpoint doit etre une URL valide.',
            's3_url.url' => 'L\'URL publique doit etre une URL valide.',
        ];
    }

    public function mount(): void
    {
        $settings = StorageSetting::getInstance();

        if ($settings->exists) {
            $this->driver = $settings->driver ?? 'local';
            $this->s3_key = $settings->s3_key ?? '';
            $this->s3_secret = ''; // Ne pas precharger le secret
            $this->s3_region = $settings->s3_region ?? 'fr-par';
            $this->s3_bucket = $settings->s3_bucket ?? '';
            $this->s3_endpoint = $settings->s3_endpoint ?? 'https://s3.fr-par.scw.cloud';
            $this->s3_url = $settings->s3_url ?? '';
            $this->s3_path_style = $settings->s3_path_style ?? true;
            $this->is_active = $settings->is_active ?? false;
        }
    }

    public function save(): void
    {
        $this->validate();

        $settings = StorageSetting::getInstance();

        $data = [
            'driver' => $this->driver,
            's3_key' => $this->s3_key,
            's3_region' => $this->s3_region,
            's3_bucket' => $this->s3_bucket,
            's3_endpoint' => $this->s3_endpoint,
            's3_url' => $this->s3_url,
            's3_path_style' => $this->s3_path_style,
            'is_active' => $this->is_active,
        ];

        // Ne met a jour le secret que s'il est renseigne
        if (!empty($this->s3_secret)) {
            $data['s3_secret'] = $this->s3_secret;
        }

        if ($settings->exists) {
            $settings->update($data);
        } else {
            if (!empty($this->s3_secret)) {
                $data['s3_secret'] = $this->s3_secret;
            }
            StorageSetting::create($data);
        }

        session()->flash('success', 'Parametres de stockage enregistres avec succes.');
    }

    public function toggleActive(): void
    {
        $this->is_active = !$this->is_active;
    }

    public function toggleSecret(): void
    {
        $this->showSecret = !$this->showSecret;
    }

    public function testConnection(): void
    {
        $this->testing = true;

        try {
            $secret = $this->s3_secret ?: StorageSetting::getInstance()->s3_secret;

            if (empty($secret)) {
                session()->flash('error', 'Le Secret Key est obligatoire pour tester la connexion.');
                $this->testing = false;
                return;
            }

            // Configure temporairement le disk S3
            config([
                'filesystems.disks.s3.driver' => 's3',
                'filesystems.disks.s3.key' => $this->s3_key,
                'filesystems.disks.s3.secret' => $secret,
                'filesystems.disks.s3.region' => $this->s3_region,
                'filesystems.disks.s3.bucket' => $this->s3_bucket,
                'filesystems.disks.s3.endpoint' => $this->s3_endpoint,
                'filesystems.disks.s3.url' => $this->s3_url,
                'filesystems.disks.s3.use_path_style_endpoint' => $this->s3_path_style,
            ]);

            // Purge le cache des disks pour forcer la reconfiguration
            app()->forgetInstance('filesystem');

            // Test: liste les fichiers du bucket
            $files = Storage::disk('s3')->files();

            session()->flash('success', 'Connexion S3 reussie ! Bucket "' . $this->s3_bucket . '" accessible (' . count($files) . ' fichiers trouves a la racine).');
        } catch (\Exception $e) {
            session()->flash('error', 'Echec de connexion S3: ' . $e->getMessage());
        }

        $this->testing = false;
    }

    public function getScalewayRegions(): array
    {
        return [
            'fr-par' => 'Paris (fr-par)',
            'nl-ams' => 'Amsterdam (nl-ams)',
            'pl-waw' => 'Varsovie (pl-waw)',
        ];
    }

    public function updatedS3Region(): void
    {
        // Met a jour l'endpoint automatiquement selon la region
        $this->s3_endpoint = 'https://s3.' . $this->s3_region . '.scw.cloud';
    }

    public function render()
    {
        return view('livewire.settings.storage-settings');
    }
}
