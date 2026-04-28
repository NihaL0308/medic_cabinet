<?php
namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class UserFactory extends Factory {
    public function definition(): array {
        return [
            'name'     => fake()->name(),
            'email'    => fake()->unique()->safeEmail(),
            'password' => Hash::make('password'),
            'role'     => 'patient',
            'phone'    => fake()->phoneNumber(),
        ];
    }

    public function medecin(): static {
        return $this->state(fn() => [
            'role'       => 'medecin',
            'specialite' => fake()->randomElement([
                'Généraliste', 'Cardiologue', 'Pédiatre',
                'Dermatologue', 'Ophtalmologue'
            ]),
        ]);
    }

    public function admin(): static {
        return $this->state(fn() => ['role' => 'admin']);
    }
}