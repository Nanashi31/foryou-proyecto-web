<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_empleado';

    protected $fillable = [
        'nombre',
        'telefono',
        'correo',
        'password_hash',
        'rol',
    ];

    public function visitas()
    {
        return $this->hasMany(Visita::class, 'id_empleado', 'id_empleado');
    }
}
