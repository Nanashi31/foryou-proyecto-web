<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
