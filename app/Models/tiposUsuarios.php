<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tiposUsuarios extends Model
{
    use HasFactory;

    protected $table    = 'tipos_usuarios';
    protected $fillable = [
        'id',
        'tipo_usuario',
        'activo'
    ];
}
