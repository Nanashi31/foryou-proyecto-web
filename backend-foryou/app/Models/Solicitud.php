<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
