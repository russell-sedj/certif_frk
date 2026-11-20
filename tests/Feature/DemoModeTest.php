<?php

use Database\Seeders\DemoSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(DemoSeeder::class);
});

it('affiche les accès de démonstration sur la page de connexion', function () {
    $this->get('/login')
        ->assertOk()
        ->assertSee('Démo — accès direct');
});

it('connecte un administrateur et affiche le tableau de bord', function () {
    $this->get('/demo/login/admin')
        ->assertRedirect(route('sessionpros.dashboard'));

    $this->assertAuthenticated();

    $this->get('/sessionpros/dashboard')
        ->assertOk()
        ->assertSee('Tableau de bord');
});

it('connecte un participant et affiche ses formations', function () {
    $this->get('/demo/login/participant')
        ->assertRedirect(route('mesformations'));

    $this->get('/mesformations')
        ->assertOk()
        ->assertSee('Comptabilité générale');
});

it('affiche les demandes au superviseur', function () {
    $this->get('/demo/login/superviseur')
        ->assertRedirect(route('sessionpros.dashboard'));

    $this->get('/demandes')
        ->assertOk()
        ->assertSee("Demandes d'attestation", false);
});
