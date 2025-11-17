<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_pago';

    protected $fillable = [
        'id_cot',
        'metodo',
        'fec_pago',
        'cantidad',
    ];

    protected $casts = [
        'fec_pago' => 'datetime',
    ];

    public function cotizacion()
    {
        return $this->belongsTo(Cotizacion::class, 'id_cot', 'id_cotizacion');
    }
}
