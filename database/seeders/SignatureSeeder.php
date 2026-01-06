<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\SignatureTemplate;
use Illuminate\Database\Seeder;

class SignatureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Creer la marque KEYMEX par defaut
        $brand = Brand::firstOrCreate(
            ['name' => 'KEYMEX'],
            [
                'description' => 'Marque KEYMEX par defaut',
                'primary_color' => '#8B5CF6',
                'secondary_color' => '#6c757d',
                'website' => 'https://keymex.fr',
                'email' => 'contact@keymex.fr',
                'logo_path' => null, // A configurer manuellement
                'is_active' => true,
                'is_default' => true,
            ]
        );

        // Creer le template de signature par defaut
        SignatureTemplate::firstOrCreate(
            ['name' => 'Template KEYMEX Standard'],
            [
                'brand_id' => $brand->id,
                'description' => 'Template de signature email standard pour les conseillers KEYMEX',
                'html_content' => $this->getDefaultTemplate(),
                'is_active' => true,
                'is_default' => true,
            ]
        );

        $this->command->info('Signature seeder complete : Brand et Template KEYMEX crees.');
    }

    /**
     * Template HTML de signature par defaut
     */
    protected function getDefaultTemplate(): string
    {
        return <<<'HTML'
<table cellpadding="0" cellspacing="0" border="0" style="font-family: Arial, Helvetica, sans-serif; font-size: 14px; line-height: 1.4; color: #333333;">
    <tr>
        <td style="padding-right: 20px; vertical-align: top;">
            <!-- Photo du conseiller -->
            <img src="{{contact.photoUrl}}" alt="Photo" style="width: 90px; height: 90px; border-radius: 50%; object-fit: cover; border: 3px solid #8B5CF6;">
        </td>
        <td style="vertical-align: top;">
            <!-- Nom et prenom -->
            <p style="margin: 0 0 5px 0; font-size: 18px; font-weight: bold; color: #1a1a1a;">
                {{contact.firstName}} {{contact.lastName}}
            </p>

            <!-- Poste -->
            <p style="margin: 0 0 10px 0; font-size: 13px; color: #8B5CF6; font-weight: 500;">
                {{contact.jobTitle}}
            </p>

            <!-- Separateur -->
            <div style="width: 60px; height: 2px; background: linear-gradient(to right, #8B5CF6, #c4b5fd); margin-bottom: 10px;"></div>

            <!-- Telephone portable -->
            <p style="margin: 0 0 5px 0; font-size: 13px;">
                <span style="color: #666;">Tel:</span>
                <a href="tel:{{contact.mobile}}" style="color: #333; text-decoration: none;">{{contact.mobile}}</a>
            </p>

            <!-- Email -->
            <p style="margin: 0 0 12px 0; font-size: 13px;">
                <span style="color: #666;">Email:</span>
                <a href="mailto:{{contact.email}}" style="color: #8B5CF6; text-decoration: none;">{{contact.email}}</a>
            </p>

            <!-- Logo KEYMEX -->
            <div style="margin-top: 10px;">
                <img src="https://keymex.fr/wp-content/uploads/2023/01/logo-keymex.png" alt="KEYMEX" style="height: 35px; width: auto;">
            </div>

            <!-- Site web -->
            <p style="margin: 8px 0 0 0; font-size: 11px;">
                <a href="{{brand.website}}" style="color: #8B5CF6; text-decoration: none;">{{brand.website}}</a>
            </p>
        </td>
    </tr>
</table>
HTML;
    }
}
