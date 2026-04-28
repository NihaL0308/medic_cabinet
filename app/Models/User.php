<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable {
    use HasFactory, Notifiable;

    protected $fillable = ['name', 'email', 'password', 'role', 'phone', 'specialite'];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array {
        return ['email_verified_at' => 'datetime', 'password' => 'hashed'];
    }

    public function appointmentsAsPatient() {
        return $this->hasMany(Appointment::class, 'patient_id');
    }

    public function appointmentsAsMedecin() {
        return $this->hasMany(Appointment::class, 'medecin_id');
    }

    public function isMedecin(): bool { return $this->role === 'medecin'; }
    public function isPatient(): bool { return $this->role === 'patient'; }
    public function isAdmin(): bool   { return $this->role === 'admin'; }
}