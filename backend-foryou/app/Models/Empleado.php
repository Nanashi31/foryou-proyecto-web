<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="Empleado",
 *     type="object",
 *     title="Empleado",
 *     required={"nombre"},
 *     @OA\Property(property="id_empleado", type="integer", format="int64", description="ID del empleado"),
 *     @OA\Property(property="nombre", type="string", description="Nombre del empleado"),
 *     @OA\Property(property="telefono", type="string", description="Número de teléfono"),
 *     @OA\Property(property="correo", type="string", format="email", description="Correo electrónico"),
 *     @OA\Property(property="rol", type="string", description="Rol del empleado (e.g., admin, herrero)"),
 *     @OA\Property(property="created_at", type="string", format="date-time", description="Fecha de creación"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", description="Fecha de actualización")
 * )
 *
 * @OA\Schema(
 *     schema="EmpleadoInput",
 *     type="object",
 *     title="Empleado Input",
 *     required={"nombre"},
 *     @OA\Property(property="nombre", type="string", description="Nombre del empleado"),
 *     @OA\Property(property="telefono", type="string", description="Número de teléfono"),
 *     @OA\Property(property="correo", type="string", format="email", description="Correo electrónico"),
 *     @OA\Property(property="password", type="string", format="password", description="Contraseña (si el empleado puede iniciar sesión)"),
 *     @OA\Property(property="rol", type="string", description="Rol del empleado (e.g., admin, herrero)")
 * )
 */
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
