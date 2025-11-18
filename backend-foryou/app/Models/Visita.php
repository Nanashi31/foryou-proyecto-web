<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="Visita",
 *     type="object",
 *     title="Visita",
 *     required={"id_empleado", "id_solicitud", "fecha"},
 *     @OA\Property(property="id_visita", type="integer", format="int64", description="ID de la visita"),
 *     @OA\Property(property="id_empleado", type="integer", format="int64", description="ID del empleado que realiza la visita"),
 *     @OA\Property(property="id_solicitud", type="integer", format="int64", description="ID de la solicitud visitada"),
 *     @OA\Property(property="fecha", type="string", format="date-time", description="Fecha y hora de la visita"),
 *     @OA\Property(property="observaciones", type="string", description="Observaciones de la visita"),
 *     @OA\Property(property="created_at", type="string", format="date-time", description="Fecha de creación"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", description="Fecha de actualización")
 * )
 *
 * @OA\Schema(
 *     schema="VisitaInput",
 *     type="object",
 *     title="Visita Input",
 *     required={"id_empleado", "id_solicitud", "fecha"},
 *     @OA\Property(property="id_empleado", type="integer", format="int64", description="ID del empleado que realiza la visita"),
 *     @OA\Property(property="id_solicitud", type="integer", format="int64", description="ID de la solicitud visitada"),
 *     @OA\Property(property="fecha", type="string", format="date-time", description="Fecha y hora de la visita"),
 *     @OA\Property(property="observaciones", type="string", description="Observaciones de la visita")
 * )
 */
class Visita extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_visita';

    protected $fillable = [
        'id_empleado',
        'id_solicitud',
        'fecha',
        'observaciones',
    ];

    protected $casts = [
        'fecha' => 'datetime',
    ];

    public function empleado()
    {
        return $this->belongsTo(Empleado::class, 'id_empleado', 'id_empleado');
    }

    public function solicitud()
    {
        return $this->belongsTo(Solicitud::class, 'id_solicitud', 'id_solicitud');
    }
}
