<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_material';

    protected $fillable = [
        'nombre',
        'stock',
        'costo_unitario',
    ];

    public function cotizaciones()
    {
        return $this->belongsToMany(Cotizacion::class, 'materiales_cotizacion', 'id_mat', 'id_cot')
                    ->withPivot('cant_usa', 'costo_unitario')
                    ->withTimestamps();
    }

    public function proyectos()
    {
        return $this->belongsToMany(Proyecto::class, 'material_proyecto', 'id_material', 'id_proyecto')
                    ->withPivot('cant_usada')
                    ->withTimestamps();
    }
}
