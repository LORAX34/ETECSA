<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServicioMatutino extends Model
{
    use HasFactory;
    protected $table = 'servicios_matutinos';
    protected $fillable = [
        'hora_programada',
        'codigo_funcion',
        'llamada_id'
    ];

    public function llamada()
    {
        return $this->belongsTo(Llamada::class);
    }
}
