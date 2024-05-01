@extends('layouts.myapp')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10 col-12">
        <div class="card">
            <div class="card-header">
                    <h2 class="text-lg font-medium text-gray-900">
                        {{ __('Modifier le mot de passe') }}
                    </h2>

                    <p class="mt-1 text-sm text-gray-600">
                        {{ __('Assurez-vous que votre compte utilise un mot de passe long et aléatoire pour rester sécurisé.') }}
                    </p>
            </div>
            <div class="card-content">
                <div class="card-body">
                <form method="post" action="{{ route('password') }}" class="mt-6 space-y-6">
                    @csrf
                    @method('put')
                    <div class="form-body">
                    <div class="row">
                         <div class="col-md-9">
                        <x-input-label for="update_password_current_password" :value="__('Mot de passe actuel')" />
                        <x-text-input id="update_password_current_password" name="current_password" type="password" class="mt-1 block w-full" autocomplete="current-password" />
                        <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
                        </div>
                    </div>

                    <div class="row">
                         <div class="col-md-9">
                        <x-input-label for="update_password_password" :value="__('Nouveau mot de passe')" />
                        <x-text-input id="update_password_password" name="password" type="password" class="mt-1 block w-full" autocomplete="new-password" />
                        <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
                        </div>
                    </div>
                    <div class="row">
                         <div class="col-md-9">
                        <x-input-label for="update_password_password_confirmation" :value="__('Confirmer le mot de passe')" />
                        <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full" autocomplete="new-password" />
                        <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
                    </div>
                    </div>

                    <div class="row">
                       <div class="col-sm-12 d-flex justify-content-center">               
                       <button type="submit" class="btn btn-primary me-1 mb-1 mt-3">Enregistrer</button>
                        @if (session('status') === 'password-updated')
                            <p
                                x-data="{ show: true }"
                                x-show="show"
                                x-transition
                                x-init="setTimeout(() => show = false, 2000)"
                                class="text-sm text-gray-600"
                            >{{ __('Enregistré.') }}</p>
                        @endif
                    </div>
                    </div>
                  </div>
                </form>
                </div>
            </div>
        </div>
    </div>
 </div>
@endsection
