<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="Cliente",
 *     type="object",
 *     title="Cliente",
 *     required={"nombre", "password_hash"},
 *     @OA\Property(
 *         property="id_cliente",
 *         type="string",
 *         format="uuid",
 *         description="ID unico del cliente"
 *     ),
 *     @OA\Property(
 *         property="nombre",
 *         type="string",
 *         description="Nombre del cliente"
 *     ),
 *     @OA\Property(
 *         property="usuario",
 *         type="string",
 *         description="Nombre de usuario"
 *     ),
 *     @OA\Property(
 *         property="telefono",
 *         type="string",
 *         description="Numero de telefono"
 *     ),
 *     @OA\Property(
 *         property="domicilio",
 *         type="string",
 *         description="Domicilio del cliente"
 *     ),
 *     @OA\Property(
 *         property="correo",
 *         type="string",
 *         format="email",
 *         description="Correo electronico del cliente"
 *     ),
 *     @OA\Property(
 *         property="created_at",
 *         type="string",
 *         format="date-time",
 *         description="Fecha de creacion"
 *     ),
 *     @OA\Property(
 *         property="updated_at",
 *         type="string",
 *         format="date-time",
 *         description="Fecha de actualizacion"
 *     )
 * )
 */
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
