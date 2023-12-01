<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class invoiceDetalle extends Model
{
    use HasFactory;

    protected $table    = 'invoice_detalle';
    protected $fillable = [
        'id_invoice',
        'nombre_producto',
        'cantidad',
        'precio'
    ];
}
