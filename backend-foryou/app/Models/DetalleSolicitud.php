<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="DetalleSolicitud",
 *     type="object",
 *     title="DetalleSolicitud",
 *     required={"id_solicitud"},
 *     @OA\Property(property="id_detalles", type="integer", format="int64", description="ID del detalle de solicitud"),
 *     @OA\Property(property="id_solicitud", type="integer", format="int64", description="ID de la solicitud a la que pertenece este detalle"),
 *     @OA\Property(property="med_alt", type="number", format="float", description="Medida de alto"),
 *     @OA\Property(property="med_anc", type="number", format="float", description="Medida de ancho"),
 *     @OA\Property(property="descripcion", type="string", description="Descripción del elemento"),
 *     @OA\Property(property="created_at", type="string", format="date-time", description="Fecha de creación"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", description="Fecha de actualización")
 * )
 *
 * @OA\Schema(
 *     schema="DetalleSolicitudInput",
 *     type="object",
 *     title="DetalleSolicitud Input",
 *     required={"id_solicitud"},
 *     @OA\Property(property="id_solicitud", type="integer", format="int64", description="ID de la solicitud a la que pertenece este detalle"),
 *     @OA\Property(property="med_alt", type="number", format="float", description="Medida de alto"),
 *     @OA\Property(property="med_anc", type="number", format="float", description="Medida de ancho"),
 *     @OA\Property(property="descripcion", type="string", description="Descripción del elemento")
 * )
 */
class DetalleSolicitud extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_detalles';
    protected $table = 'detalles_solicitud';

    protected $fillable = [
        'id_solicitud',
        'med_alt',
        'med_anc',
        'descripcion',
    ];

    public function solicitud()
    {
        return $this->belongsTo(Solicitud::class, 'id_solicitud', 'id_solicitud');
    }
}
