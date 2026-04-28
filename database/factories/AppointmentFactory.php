<?php
namespace Database\Factories;

use App\Models\User;
use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;

class AppointmentFactory extends Factory {
    public function definition(): array {
        return [
            'patient_id'       => User::where('role', 'patient')->inRandomOrder()->first()->id,
            'medecin_id'       => User::where('role', 'medecin')->inRandomOrder()->first()->id,
            'service_id'       => Service::inRandomOrder()->first()->id,
            'appointment_date' => fake()->dateTimeBetween('now', '+2 months')->format('Y-m-d'),
            'appointment_time' => fake()->randomElement(['09:00','09:30','10:00','10:30','11:00','14:00','14:30','15:00','15:30','16:00']),
            'statut'           => fake()->randomElement(['en_attente', 'confirme', 'annule', 'termine']),
            'notes'            => fake()->optional()->sentence(),
        ];
    }
}