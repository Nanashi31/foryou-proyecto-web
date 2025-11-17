<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_cliente';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'nombre',
        'usuario',
        'password_hash',
        'telefono',
        'domicilio',
        'correo',
        'auth_user_id',
    ];

    public function solicitudes()
    {
        return $this->hasMany(Solicitud::class, 'id_cliente', 'id_cliente');
    }

    public function proyectos()
    {
        return $this->hasMany(Proyecto::class, 'id_cliente', 'id_cliente');
    }
}
