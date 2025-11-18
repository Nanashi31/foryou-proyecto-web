<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="Cotizacion",
 *     type="object",
 *     title="Cotizacion",
 *     required={"id_solicitud", "costo_total"},
 *     @OA\Property(property="id_cotizacion", type="integer", format="int64", description="ID de la cotización"),
 *     @OA\Property(property="id_solicitud", type="integer", format="int64", description="ID de la solicitud asociada"),
 *     @OA\Property(property="fecha_cot", type="string", format="date-time", description="Fecha de la cotización"),
 *     @OA\Property(property="costo_total", type="number", format="float", description="Costo total de la cotización"),
 *     @OA\Property(property="notas", type="string", description="Notas adicionales de la cotización"),
 *     @OA\Property(property="created_at", type="string", format="date-time", description="Fecha de creación"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", description="Fecha de actualización")
 * )
 *
 * @OA\Schema(
 *     schema="CotizacionInput",
 *     type="object",
 *     title="Cotizacion Input",
 *     required={"id_solicitud", "costo_total"},
 *     @OA\Property(property="id_solicitud", type="integer", format="int64", description="ID de la solicitud asociada"),
 *     @OA\Property(property="costo_total", type="number", format="float", description="Costo total de la cotización"),
 *     @OA\Property(property="notas", type="string", description="Notas adicionales de la cotización")
 * )
 */
class Cotizacion extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_cotizacion';

    protected $fillable = [
        'id_solicitud',
        'fecha_cot',
        'costo_total',
        'notas',
    ];

    protected $casts = [
        'fecha_cot' => 'datetime',
    ];

    public function solicitud()
    {
        return $this->belongsTo(Solicitud::class, 'id_solicitud', 'id_solicitud');
    }

    public function pagos()
    {
        return $this->hasMany(Pago::class, 'id_cot', 'id_cotizacion');
    }

    public function materiales()
    {
        return $this->belongsToMany(Material::class, 'materiales_cotizacion', 'id_cot', 'id_mat')
                    ->withPivot('cant_usa', 'costo_unitario')
                    ->withTimestamps();
    }
}
