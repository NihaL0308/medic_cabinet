# medic_cabinet

Application Laravel de gestion d'un cabinet medical: utilisateurs, services et rendez-vous.

## Prerequis

- PHP 8.3+
- Composer
- Node.js et npm
- MySQL 

## Installation

```bash
git clone <url-du-projet>
cd medic_cabinet
composer install
cp .env.example .env
php artisan key:generate
```

Configurez ensuite la base de donnees dans `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=cab_med
DB_USERNAME=root
DB_PASSWORD=123456
```

Puis lancez:

```bash
php artisan migrate --seed
npm install
npm run build
php artisan serve
```

## Comptes utiles

Si vos seeders creent les comptes de demo, vous pouvez utiliser par exemple:

- Admin: `admin@cabinet.com` / `password`
- Patient: `patient@cabinet.ma` / `password`
- Medecin: `medecin@cabinet.com` / `password`

## Fonctionnalites

- Authentification utilisateur
- Gestion des rendez-vous
- Gestion des services
- Gestion des utilisateurs
- API simple pour les rendez-vous

## Routes principales

- Web: `routes/web.php`
- API: `routes/api.php`

API rendez-vous disponible sur:

- `GET /api/appointments`
- `GET /api/appointments/{id}`
- `POST /api/appointments`

## Structure utile

```text
app/
  Http/Controllers/
    Api/AppointmentApiController.php
    AppointmentController.php
    ServiceController.php
    UserController.php
  Models/
    Appointment.php
    Service.php
    User.php
resources/views/
  appointments/partials/
  services/
  users/
routes/
  api.php
  web.php
```

## Tests rapides

```bash
php artisan route:list
php artisan test
```
