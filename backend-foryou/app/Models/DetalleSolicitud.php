<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
