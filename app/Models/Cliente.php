<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'carnet',
        'direccion',
        'telefono',
        'tipo',
        'activo',
        'moroso',
        'matutino',
        'rastreo',
        'linea_arrendada'
    ];

    public function llamadas()
    {
        return $this->hasMany(Llamada::class, 'numero_origen', 'telefono');
    }

    public function pagosMensuales()
    {
        return $this->hasMany(PagoMensual::class, 'cliente_id');
    }
}
