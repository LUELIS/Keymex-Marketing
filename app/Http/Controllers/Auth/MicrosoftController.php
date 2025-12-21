<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class MicrosoftController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('microsoft')->redirect();
    }

    public function callback()
    {
        try {
            $microsoftUser = Socialite::driver('microsoft')->user();

            // Vérifier que l'email appartient au domaine KEYMEX
            $email = $microsoftUser->getEmail();
            if (!str_ends_with($email, '@keymex.fr') && !str_ends_with($email, '@keymex.com')) {
                return redirect()->route('login')
                    ->with('error', 'Seuls les comptes KEYMEX sont autorisés.');
            }

            $user = User::updateOrCreate(
                ['email' => $email],
                [
                    'name' => $microsoftUser->getName(),
                    'microsoft_id' => $microsoftUser->getId(),
                    'avatar' => $microsoftUser->getAvatar(),
                    'password' => bcrypt(str()->random(32)),
                ]
            );

            Auth::login($user, true);

            return redirect()->intended(route('orders.index'));

        } catch (\Exception $e) {
            return redirect()->route('login')
                ->with('error', 'Erreur lors de la connexion Microsoft: ' . $e->getMessage());
        }
    }

    public function logout()
    {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect('/');
    }
}
