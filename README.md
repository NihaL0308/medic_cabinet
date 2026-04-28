<p align="center">
  <a href="https://laravel.com" target="_blank">
    <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo">
  </a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax.

---

# 🏥 medic_cabinet

Medical Cabinet Management System built with Laravel.

## ⚙️ Installation

```bash
git clone https://github.com/NihaL0308/medic_cabinet.git


DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=cab_med
DB_USERNAME=root
DB_PASSWORD=123456

les commandes utiliser :
php artisan migrate
php artisan db:seed
php artisan migrate:fresh --seed
php artisan serve

name' => 'Omar Ait Lahcen',
            'email' => 'patient@cabinet.ma',
            'password' => Hash::make('password'),
            'role' => 'patient'
User::create([
            'name' => 'Youssef El Mansouri',
            'email' => 'admin@cabinet.com',
            'password' => Hash::make('password'),
            'role' => 'admin'
        ]);

        User::factory()->medecin()->create([
            'name' => 'Dr. Sara El Malki',
            'email' => 'medecin@cabinet.com',
        ]);

app/
├── Http/
│   ├── Controllers/
│   │   ├── Api/
│   │   │   └── AppointmentApiController.php
│   │   ├── Auth/
│   │   ├── AppointmentController.php
│   │   ├── ServiceController.php
│   │   └── UserController.php
├── Models/
│   ├── Appointment.php
│   ├── Service.php
│   └── User.php

resources/
└── views/
    ├── appointments/
    ├── services/
    └── users/

routes/
├── web.php
└── api.php