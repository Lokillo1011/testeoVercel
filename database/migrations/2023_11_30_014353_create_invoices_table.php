<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            //nombre del invoice
            $table->string('nombre_invoice', 100);
            //nombre de la compania
            $table->string('nombre_compania', 100);
            //url sitio web de la compania
            $table->string('url_sitio_web', 100);
            //numero de la compania
            $table->text('numero_compania');
            //correo electronico de la compania
            $table->string('correo_electronico', 100);
            //logo de la compania en un campo de texto
            $table->text('url_logo');
            //direccion de la compania
            $table->string('direccion', 100);
            //nombre del cliente
            $table->string('nombre_cliente', 100);
            //correo electronico del cliente
            $table->string('correo_electronico_cliente', 100);
            //numero de factura
            $table->text('numero_factura');
            //fecha de la factura
            $table->date('fecha_factura');
            //fecha de vencimiento de la factura
            $table->date('fecha_vencimiento_factura');
            //comentario de la factura
            $table->text('comentario_factura')->nullable();
            //subtotal de la factura
            $table->decimal('subtotal_factura', 8, 2);
            //impuesto de la factura
            $table->decimal('impuesto_factura', 8, 2);
            //total de la factura
            $table->decimal('total_factura', 8, 2);
            //activo
            $table->integer('activo')->default(1);
            //id del usuario
            $table->integer('id_usuario');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
