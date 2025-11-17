<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proyecto extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_proyecto';

    protected $fillable = [
        'observaciones',
        'plano_url',
        'plano_json',
        'id_cliente',
    ];

    protected $casts = [
        'plano_json' => 'array',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'id_cliente', 'id_cliente');
    }

    public function materiales()
    {
        return $this->belongsToMany(Material::class, 'material_proyecto', 'id_proyecto', 'id_material')
                    ->withPivot('cant_usada')
                    ->withTimestamps();
    }
}
