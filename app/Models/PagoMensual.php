<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PagoMensual extends Model
{
    use HasFactory;
    protected $table = 'pagos_mensuales';
    protected $fillable = [
        'mes',
        'año',
        'total_llamadas',
        'total_servicios',
        'total',
        'cliente_id'
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }
}
