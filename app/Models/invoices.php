<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class invoices extends Model
{
    use HasFactory;

    protected $table    = 'invoices';
    protected $fillable = [
        'id',
        'nombre_invoice',
        'nombre_compania',
        'url_sitio_web',
        'numero_compania',
        'correo_electronico',
        'url_logo',
        'direccion',
        'nombre_cliente',
        'correo_electronico_cliente',
        'numero_factura',
        'fecha_factura',
        'fecha_vencimiento_factura',
        'comentario_factura',
        'subtotal_factura',
        'impuesto_factura',
        'impuesto_factura',
        'descuento_factura',
        'total_factura',
        'activo',
        'id_usuario'

    ];

    public function invoiceDetalle()
    {
        return $this->hasMany(invoiceDetalle::class, 'id_invoice', 'id');
    }
}
