<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */protected $model = User::class;
    public function definition(): array
    {  $sexes = ['M', 'F']; // Liste des statuts possibles
       //$titres = ['DGTCP', 'DFPT']; 
       $roles = ['admin', 'superviseur','participant']; 
       
        return [
            'matricule'=>fake()->numberBetween(1,2000),
            'name' => fake()->lastName(),
            'surname' => fake()->firstName(),
            'sexe' => $this->faker->randomElement($sexes),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            //'titre' => $this->faker->unique()->randomElement($titres),
            'role' => $this->faker->randomElement($roles),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
