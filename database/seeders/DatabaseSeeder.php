<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Service;
use App\Models\Appointment;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // =========================
        // ADMIN
        // =========================
        User::create([
            'name' => 'Youssef El Mansouri',
            'email' => 'admin@cabinet.com',
            'password' => Hash::make('password'),
            'role' => 'admin'
        ]);

        // =========================
        // MAIN DOCTOR
        // =========================
        User::factory()->medecin()->create([
            'name' => 'Dr. Sara El Malki',
            'email' => 'medecin@cabinet.com',
        ]);

        // =========================
        // MAIN PATIENT
        // =========================
        User::create([
            'name' => 'Omar Ait Lahcen',
            'email' => 'patient@cabinet.ma',
            'password' => Hash::make('password'),
            'role' => 'patient'
        ]);

        // =========================
        // RANDOM DOCTORS
        // =========================
        User::factory()->count(4)->medecin()->create();

        // =========================
        // RANDOM PATIENTS
        // =========================
        User::factory()->count(10)->create([
            'role' => 'patient'
        ]);

        // =========================
        // SERVICES
        // =========================
        Service::factory()->count(5)->create();

        // =========================
        // APPOINTMENTS
        // =========================
        Appointment::factory()->count(20)->create();
    }
}