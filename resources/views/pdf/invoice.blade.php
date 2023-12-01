<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<table style="width: 100%; border-collapse: collapse; font-family: Arial, sans-serif; border: none;">
    <tr>
        <td colspan="2" style="border: 1px solid #ddd; padding: 10px;">
            <h2 style="font-size: 20px; margin-bottom: 10px;">
                {{$invoice->nombre_compania}}
            </h2>
            <p style="font-size: 14px; color: #757575; margin: 0;">
                {{$invoice->url_sitio_web}}
            </p>
            <p style="font-size: 14px; color: #757575; margin: 0;">
                {{$invoice->numero_compania}}
            </p>
            <p style="font-size: 14px; color: #757575; margin: 0;">
                {{$invoice->direccion}}
            </p>
            <p style="font-size: 14px; color: #757575; margin: 0;">
                {{$invoice->correo_electronico}}
            </p>
        </td>

        <td style="text-align: center; border: 1px solid #ddd; padding: 10px;">
            <!-- Drag and Drop para agregar una imagen del logo -->
            <div style="border: 1px solid #ddd; padding: 10px;">
                <img id="logoPreview" src="data:image/png;base64,{{$invoice->url_logo}}" alt="Logo"
                     style="display: block; width: 150px; height: auto;">
            </div>
        </td>
    </tr>

    <tr>
        <td colspan="2" style="border: 1px solid #ddd; padding: 10px;">
            <!-- Datos del cliente -->
            <h2 style="font-size: 20px; margin-bottom: 10px;">
                {{$invoice->nombre_cliente}}
            </h2>
            <p style="font-size: 14px; color: #757575; margin: 0;">
                {{$invoice->correo_electronico_cliente}}
            </p>
        </td>

        <td style="border: 1px solid #ddd; padding: 10px;">
            <!-- Datos de la factura -->
            <div style="border: 1px solid #ddd; padding: 10px;">
                <div style="margin-bottom: 10px;">
                    <span style="font-size: 14px;">Factura:</span>
                    <span id="InvoiceNo" style="font-size: 14px; margin-left: 10px;">{{$invoice->numero_factura}}</span>
                </div>
                <div style="margin-bottom: 10px;">
                    <span style="font-size: 14px;">Fecha de Emisión:</span>
                    <span id="InvoiceDate"
                          style="font-size: 14px; margin-left: 10px;">{{$invoice->fecha_factura}}</span>
                </div>
                <div>
                    <span style="font-size: 14px;">Fecha de Vencimiento:</span>
                    <span id="InvoiceDueDate"
                          style="font-size: 14px; margin-left: 10px;">{{$invoice->fecha_vencimiento_factura}}</span>
                </div>
            </div>
        </td>
    </tr>

    <tr>
        <td colspan="3" style="border: 1px solid #ddd; padding: 10px;">
            <!-- Detalles de la factura -->
            <h4 style="margin-bottom: 10px;">Detalles de la Factura:</h4>
            <table style="width: 100%; border-collapse: collapse; margin-bottom: 10px;">
                <thead>
                <tr>
                    <th style="border: 1px solid #ddd; padding: 8px;">Producto</th>
                    <th style="border: 1px solid #ddd; padding: 8px;">Cantidad</th>
                    <th style="border: 1px solid #ddd; padding: 8px;">Precio</th>
                </tr>
                </thead>
                <tbody>
                @foreach($invoice->invoiceDetalle as $detalle)
                    {{--                    centra la informacion--}}
                    <tr>
                        <td style="border: 1px solid #ddd; padding: 8px; text-align: center;">{{$detalle->nombre_producto}}</td>
                        <td style="border: 1px solid #ddd; padding: 8px; text-align: center;">{{$detalle->cantidad}}</td>
                        <td style="border: 1px solid #ddd; padding: 8px; text-align: center;">{{$detalle->precio}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </td>
    </tr>

    <!-- Tu contenido existente aquí -->
    <tr>
        <td colspan="2" style="border: 1px solid #ddd; padding: 10px;">
            <!-- En 2 columnas debe ir lo siguiente: en la primera irá un textarea para comentarios -->
            <h6 style="margin-bottom: 10px;">Comentarios:</h6>
            <textarea style="width: 100%;" id="exampleFormControlTextarea1"
                      rows="3">{{$invoice->comentario_factura}}</textarea>
        </td>
        <td style="border: 1px solid #ddd; padding: 10px; width: 20%; text-align: right;">
            <!-- En la segunda, es el subtotal, impuestos, Descuento y debajo el total -->
            <div>
                <div style="margin-bottom: 10px;">
                    <span style="font-size: 14px;">Subtotal:</span>
                    <span id="subtotal"
                          style="font-size: 14px; margin-left: 10px;">{{$invoice->subtotal_factura}}</span>
                </div>
                <div style="margin-bottom: 10px;">
                    <span style="font-size: 14px;">Impuestos:</span>
                    <span id="taxes" style="font-size: 14px; margin-left: 10px;">{{$invoice->impuesto_factura}}</span>
                </div>
                <div>
                    <span style="font-size: 14px;">Total:</span>
                    <span id="total" style="font-size: 14px; margin-left: 10px;">{{$invoice->total_factura}}</span>
                </div>
            </div>
        </td>
    </tr>
</table>
</body>
</html>
