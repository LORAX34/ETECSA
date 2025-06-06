<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Llamada extends Model
{
    use HasFactory;

    protected $fillable = [
        'minutos',
        'fecha_hora',
        'numero_origen',
        'numero_destino',
        'es_internacional',
        'es_nacional',
        'es_local',
        'es_servicio',
        'es_tele_seleccion',
        'cod_local_origen',
        'cod_local_dest',
        'cod_pais_dest',
        'tarifa_pais',
        'precio',
        'cliente_id'
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function paisDestino()
    {
        return $this->belongsTo(Pais::class, 'cod_pais_dest', 'codigo_pais');
    }

    public function servicioMatutino()
    {
        return $this->hasOne(ServicioMatutino::class, 'llamada_id');
    }
}
