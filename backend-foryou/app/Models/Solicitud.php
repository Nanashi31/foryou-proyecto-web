<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="Solicitud",
 *     type="object",
 *     title="Solicitud",
 *     required={"direccion"},
 *     @OA\Property(property="id_solicitud", type="integer", format="int64", description="ID de la solicitud"),
 *     @OA\Property(property="direccion", type="string", description="Dirección del trabajo"),
 *     @OA\Property(property="descripcion", type="string", description="Descripción del proyecto"),
 *     @OA\Property(property="fecha", type="string", format="date-time", description="Fecha de creación de la solicitud"),
 *     @OA\Property(property="id_cliente", type="string", format="uuid", description="ID del cliente que hace la solicitud"),
 *     @OA\Property(property="dias_disponibles", type="string", description="Días disponibles del cliente"),
 *     @OA\Property(property="fecha_cita", type="string", format="date-time", description="Fecha y hora de la cita"),
 *     @OA\Property(property="materiales", type="string", description="Materiales sugeridos"),
 *     @OA\Property(property="tipo_proyecto", type="string", description="Tipo de proyecto"),
 *     @OA\Property(property="created_at", type="string", format="date-time", description="Fecha de creación"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", description="Fecha de actualización")
 * )
 *
 * @OA\Schema(
 *     schema="SolicitudInput",
 *     type="object",
 *     title="Solicitud Input",
 *     required={"direccion"},
 *     @OA\Property(property="direccion", type="string", description="Dirección del trabajo"),
 *     @OA\Property(property="descripcion", type="string", description="Descripción del proyecto"),
 *     @OA\Property(property="id_cliente", type="string", format="uuid", description="ID del cliente que hace la solicitud"),
 *     @OA\Property(property="dias_disponibles", type="string", description="Días disponibles del cliente"),
 *     @OA\Property(property="fecha_cita", type="string", format="date-time", description="Fecha y hora de la cita"),
 *     @OA\Property(property="materiales", type="string", description="Materiales sugeridos"),
 *     @OA\Property(property="tipo_proyecto", type="string", description="Tipo de proyecto")
 * )
 */
class Solicitud extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_solicitud';

    protected $fillable = [
        'direccion',
        'descripcion',
        'fecha',
        'id_cliente',
        'dias_disponibles',
        'fecha_cita',
        'materiales',
        'tipo_proyecto',
    ];

    protected $casts = [
        'fecha' => 'datetime',
        'fecha_cita' => 'datetime',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'id_cliente', 'id_cliente');
    }

    public function detallesSolicitud()
    {
        return $this->hasMany(DetalleSolicitud::class, 'id_solicitud', 'id_solicitud');
    }

    public function cotizacion()
    {
        return $this->hasOne(Cotizacion::class, 'id_solicitud', 'id_solicitud');
    }

    public function visitas()
    {
        return $this->hasMany(Visita::class, 'id_solicitud', 'id_solicitud');
    }
}
