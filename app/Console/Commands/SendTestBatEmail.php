<?php

namespace App\Console\Commands;

use App\Mail\BatValidationMail;
use App\Models\StandaloneBat;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendTestBatEmail extends Command
{
    protected $signature = 'bat:send-test {email : Email de destination}';
    protected $description = 'Envoie un email de test BAT';

    public function handle(): int
    {
        $email = $this->argument('email');

        // Recupere un BAT existant ou cree un fake
        $bat = StandaloneBat::with(['supportType', 'format', 'category'])->first();

        if (!$bat) {
            // Cree un BAT de test temporaire (non sauvegarde)
            $bat = new StandaloneBat([
                'advisor_name' => 'Jean Dupont',
                'advisor_email' => $email,
                'title' => 'Flyer Prospection - Test',
                'description' => 'Flyer A5 recto/verso pour prospection immobiliere dans le secteur de Lyon 6eme.',
                'quantity' => 500,
                'grammage' => '350g couche brillant',
                'delivery_time' => '5 jours ouvrés',
                'validation_token' => 'test-token-' . uniqid(),
            ]);
        }

        $this->info("Envoi de l'email de test BAT a {$email}...");

        try {
            Mail::to($email)->send(new BatValidationMail($bat));
            $this->info('Email envoye avec succes !');
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error('Erreur: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
