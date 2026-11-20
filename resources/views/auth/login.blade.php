<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4">
            @if (! config('demo.enabled') && Route::has('password.request'))
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <x-primary-button class="ms-3">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>

    @if (config('demo.enabled'))
        <div class="mt-6 border-t border-gray-200 pt-5">
            <p class="text-sm font-semibold text-gray-700">Démo — accès direct</p>
            <p class="mt-1 text-xs text-gray-500">
                Données fictives. Choisissez un profil pour découvrir l'application sans créer de compte.
            </p>

            <div class="mt-3 space-y-2">
                <a href="{{ route('demo.login', 'admin') }}"
                   class="flex items-center justify-between rounded-md border border-gray-300 px-3 py-2 text-sm text-gray-700 hover:bg-gray-50">
                    <span>
                        <strong>Administrateur</strong><br>
                        <span class="text-xs text-gray-500">Formations, agents, catégories et lieux</span>
                    </span>
                    <span aria-hidden="true">&rarr;</span>
                </a>

                <a href="{{ route('demo.login', 'superviseur') }}"
                   class="flex items-center justify-between rounded-md border border-gray-300 px-3 py-2 text-sm text-gray-700 hover:bg-gray-50">
                    <span>
                        <strong>Superviseur</strong><br>
                        <span class="text-xs text-gray-500">Sessions, présences et demandes d'attestation</span>
                    </span>
                    <span aria-hidden="true">&rarr;</span>
                </a>

                <a href="{{ route('demo.login', 'participant') }}"
                   class="flex items-center justify-between rounded-md border border-gray-300 px-3 py-2 text-sm text-gray-700 hover:bg-gray-50">
                    <span>
                        <strong>Participant</strong><br>
                        <span class="text-xs text-gray-500">Mes formations et mes demandes d'attestation</span>
                    </span>
                    <span aria-hidden="true">&rarr;</span>
                </a>
            </div>

            <details class="mt-3 text-xs text-gray-500">
                <summary class="cursor-pointer">Comptes et mot de passe</summary>
                <ul class="mt-2 space-y-1">
                    <li><code>admin@demo.certifs.test</code> / <code>{{ config('demo.password') }}</code></li>
                    <li><code>superviseur@demo.certifs.test</code> / <code>{{ config('demo.password') }}</code></li>
                    <li><code>participant@demo.certifs.test</code> / <code>{{ config('demo.password') }}</code></li>
                </ul>
            </details>

            <p class="mt-3 text-xs text-gray-500">
                Les données sont remises à zéro automatiquement chaque nuit.
            </p>

            <form method="POST" action="{{ route('demo.reset') }}" class="mt-2"
                  onsubmit="return confirm('Réinitialiser toutes les données de la démo ?');">
                @csrf
                <button type="submit" class="text-xs text-gray-500 underline hover:text-gray-700">
                    Réinitialiser la démo maintenant
                </button>
            </form>
        </div>
    @endif
</x-guest-layout>
