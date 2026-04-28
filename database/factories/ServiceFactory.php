<?php
namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ServiceFactory extends Factory {
    public function definition(): array {
        $services = [
    ['name' => 'Consultation médicale',     'duree' => 30, 'prix' => 180],
    ['name' => 'Suivi cardiologique',       'duree' => 45, 'prix' => 350],
    ['name' => 'Bilan complet',             'duree' => 60, 'prix' => 600],
    ['name' => 'Échographie',               'duree' => 25, 'prix' => 250],
    ['name' => 'Analyse biologique',        'duree' => 20, 'prix' => 120],
];
        $s = fake()->randomElement($services);
        return [
            'name'          => $s['name'],
            'description'   => fake()->sentence(),
            'duree_minutes' => $s['duree'],
            'prix'          => $s['prix'],
        ];
    }
}