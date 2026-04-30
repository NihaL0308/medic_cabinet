<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'medecin_id',
        'name',
        'description',
        'duree_minutes',
        'prix',
    ];

    public function medecin()
    {
        return $this->belongsTo(User::class, 'medecin_id');
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
}
