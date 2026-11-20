<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;

class DemoController extends Controller
{
    /**
     * Connecte directement le visiteur avec un compte de démonstration.
     */
    public function login(string $role): RedirectResponse
    {
        abort_unless(config('demo.enabled'), 404);

        $email = config("demo.accounts.$role");
        abort_unless(is_string($email), 404);

        $user = User::where('email', $email)->first();
        abort_unless($user, 404, 'Compte de démonstration introuvable. Réinitialisez la démo.');

        Auth::login($user);
        request()->session()->regenerate();

        return redirect()->route(
            in_array($role, ['admin', 'superviseur'], true)
                ? 'sessionpros.dashboard'
                : 'mesformations'
        );
    }

    /**
     * Remet les données de démonstration à zéro.
     */
    public function reset(Request $request): RedirectResponse
    {
        abort_unless(config('demo.enabled'), 404);

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        try {
            Artisan::call('demo:reset', ['--force' => true]);
        } catch (\Throwable $exception) {
            report($exception);

            return redirect()
                ->route('login')
                ->withErrors(['demo' => 'La réinitialisation de la démo a échoué.']);
        }

        return redirect()
            ->route('login')
            ->with('status', 'La démo a été réinitialisée avec succès.');
    }
}
