<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pais extends Model
{
    use HasFactory;
    protected $table = 'paises';
    protected $primaryKey = 'codigo_pais';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'codigo_pais',
        'nombre',
        'tarifa'
    ];
}
