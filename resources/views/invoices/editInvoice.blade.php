@extends('layouts._blankNav')
@section('titulo',"Inicio")
@section('css')
    <style>
        body {
            background-color: #f8f9fa;
        }

        .invoice-container {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin: 50px auto;
            max-width: 800px;
        }

        .editable {
            border: none;
            outline: none;
            cursor: pointer;
        }

        .invoice-header {
            text-align: center;
            margin-bottom: 20px;
        }

        .invoice-details,
        .invoice-items {
            margin-top: 20px;
        }

        .invoice-footer {
            margin-top: 30px;
        }

        .logo-container {
            text-align: -webkit-center;
            text-align: -moz-center;
            text-align: center;
            width: 50%;
        }

        .logo-container img {
            width: 100%;
            max-width: 200px; /* Establece el tamaño máximo del logo */
            height: auto; /* Ajusta automáticamente la altura para mantener la proporción */


        }

        .drag-drop-area {
            border: 2px dashed #c1c1c1;
            text-align: center;
            cursor: pointer;
        }

        #fileInput {
            display: none; /* Ocultar el input de tipo file */
        }
    </style>
@endsection
@section('contenido')

    <div class="container-fluid"><h3 class="text-dark mb-4">Editar Invoice</h3>
        <div class="card shadow">
            <div class="card-header py-3"><p class="text-primary m-0 fw-bold"> {{$invoice->nombre_invoice}}</p></div>
            <div class="card-body">
                <div class="invoice-container">


                    <div class="row">
                        <div class="col-md-6">
                            <!-- Datos del emisor -->
                            <div class="invoice-details">
                                <h2 style="font-size: 20px"><p id="nombre_compania" class="editable"
                                                               ondblclick="makeEditable('nombre_compania', 'Nombre de la Compañía')">{{$invoice->nombre_compania}}</p>
                                </h2>
                                <h6 style="font-size:14px;color:#757575;"><p id="url_sitio_web" class="editable"
                                                                             ondblclick="makeEditable('url_sitio_web', 'URL del Sitio: www.tuempresa.com')">{{$invoice->url_sitio_web}}</p>
                                </h6>
                                <h6 style="font-size:14px;color:#757575;"><p id="numero_compania" class="editable"
                                                                             ondblclick="makeEditable('numero_compania', 'Número de Teléfono: 123-456-789')">{{$invoice->numero_compania}}</p>
                                </h6>
                                <h6 style="font-size:14px;color:#757575;"><p id="direccion" class="editable"
                                                                             ondblclick="makeEditable('direccion', 'Dirección: Dirección de la Compañía')">{{$invoice->direccion}}</p>
                                </h6>
                                <h6 style="font-size:14px;color:#757575;"><p id="correo_electronico" class="editable"
                                                                             ondblclick="makeEditable('correo_electronico', 'Correo: info@tuempresa.com')">{{$invoice->correo_electronico}}</p>
                                </h6>
                            </div>
                        </div>

                        <div class="col-md-6" style="text-align: -webkit-center;">
                            <!-- Drag and Drop para agregar una imagen del logo -->
                            <div class="invoice-details">
                                <div class="logo-container">
                                    <div class="drag-drop-area" onclick="triggerFileInput()">
                                        {{--                                        {{$invoice->url_logo}} es un base64--}}
                                        {{--                                        --}}
                                        <img id="logoPreview" src="data:image/png;base64,{{$invoice->url_logo}}"
                                             alt="Logo" style="display: block;">
                                        <p style="display: none" id="logoPlaceholder">Arrastra y suelta el logo aquí o
                                            haz clic para
                                            seleccionar y editarlo</p>
                                        <input type="file" id="fileInput" accept="image/*"
                                               onchange="handleFileSelect(event)">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">

                        <div class="col-md-6">
                            <!-- Datos del cliente -->
                            <div class="invoice-details">
                                <h2 style="font-size: 20px"><p id="nombre_cliente" class="editable"
                                                               ondblclick="makeEditable('nombre_cliente', 'Nombre del Cliente')">{{$invoice->nombre_cliente}}</p>
                                </h2>
                                <h6 style="font-size:14px;color:#757575;"><p id="correo_cliente" class="editable"
                                                                             ondblclick="makeEditable('correo_cliente', 'Correo del Cliente')">
                                        {{$invoice->correo_electronico_cliente}}</p></h6>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <!-- Datos del cliente -->
                            <div class="invoice-details">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h6>Factura:</h6>
                                    </div>
                                    <div class="col-md-6">
                                        <p id="numero_factura" class="editable"
                                           ondblclick="makeEditable('numero_factura', 'Número de Factura: ###')">{{$invoice->numero_factura}}</p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <h6>Fecha de Emisión:</h6>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="date" id="fecha_factura" class="editable"
                                               value="{{$invoice->fecha_factura}}"
                                               ondblclick="makeEditable('fecha_factura', '01/01/2021')">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <h6>Fecha de Vencimiento:</h6>
                                    </div>
                                    <div class="col-md-6">
                                        <!--                        Input date-->
                                        <input type="date" id="fecha_vencimiento" class="editable"
                                               value="{{$invoice->fecha_vencimiento_factura}}"
                                               ondblclick="makeEditable('fecha_vencimiento', '01/01/2021')">

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="invoice-items">
                        <!-- Agregar una tabla para los detalles de la factura con DataTables -->
                        <h4>Detalles de la Factura:</h4>
                        <table id="invoiceTable" class="display dataTable dtr-inline collapsed d-block"
                               style="overflow-x: auto; padding-left:0; padding-rigth:0;width: 100% !important;"
                               role="grid"
                               aria-describedby="example_info">
                            <thead>
                            <tr>
                                <th>id</th>
                                <th>Producto</th>
                                <th>Cantidad</th>
                                <th>Precio</th>
                                <th>Acciones</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($invoice->invoiceDetalle as $detalle)
                                <tr>
                                    <td>{{$detalle->id}}</td>
                                    <td>{{$detalle->nombre_producto}}</td>
                                    <td>{{$detalle->cantidad}}</td>
                                    <td>${{$detalle->precio}}</td>
                                    <td>
                                        <button class="btn btn-danger" onclick="removeInvoiceItem(this)">Eliminar
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Agregar un botón para agregar elementos a la tabla -->
                    <button class="btn btn-primary mt-2" onclick="addInvoiceItem()">Agregar Producto</button>

                    <!-- Tu contenido existente aquí -->
                    <div class="invoice-footer">
                        <!--      En 2 columnas debe de ir lo siguiente, en la primera ira un text area para comentarios-->
                        <!--        En la 2da Es el subtotal, impuestos, Descuento y debajo el total-->
                        <div class="row">
                            <div class="col-md-6">
                                <h6>Comentarios:</h6>
                                <textarea class="form-control" name="comentario_factura" id="comentario_factura"
                                          rows="3">{{$invoice->comentario_factura}}</textarea>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h6>Subtotal:</h6>
                                    </div>
                                    <div class="col-md-6">
                                        <p id="subtotal_factura" class="editable">{{$invoice->subtotal_factura}}</p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <h6>Impuestos:</h6>
                                    </div>
                                    <div class="col-md-6">
                                        <p id="impuesto_factura" class="editable">{{$invoice->impuesto_factura}}</p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <h6>Total:</h6>
                                    </div>
                                    <div class="col-md-6">
                                        <p id="total_factura" class="editable">{{$invoice->total_factura}}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Agregar un botón para guardar la factura situado en el footer de la tarjeta -->
            {{--            Y otro de vista previa--}}
            {{--            Ambos de lado derecho de la pantalla--}}
            <div class="card-footer">
                <div class="row">
                    <div class="col-md-6">
                        <a type="button" class="btn btn-warning mt-2" onclick="saveInvoice()">Editar</a>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="modalConfirmarEnvio" tabindex="-1" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <input hidden="hidden" id="id_invoice" value="{{$invoice->id}}">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Modificar Invoice?</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    {{--                    Agrega un input para poder escribir un nombre para el archivo--}}
                    <input type="text" class="form-control" id="nombre_archivo" placeholder="Nombre del archivo"
                           value="{{$invoice->nombre_invoice}}">
                    <p>Al guardar el invoice se enviará una copia a tu correo de registro.</p>
                    <p>¿Deseas continuar?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No, cerrar</button>
                    <a type="button" onclick="sendInvoice()" class="btn btn-primary">Si, Modificar Invoice</a>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')

    <script>
        function makeEditable(elementId, placeholder) {
            var element = document.getElementById(elementId);
            var currentValue = element.innerHTML;
            element.innerHTML = `<input type="text" id="${elementId}Input" placeholder="${placeholder}" value="${currentValue}" onblur="makeNonEditable('${elementId}', '${placeholder}')">`;
            document.getElementById(`${elementId}Input`).focus();
        }

        function makeNonEditable(elementId, placeholder) {
            var inputElement = document.getElementById(`${elementId}Input`);
            var value = inputElement.value;
            var originalElement = document.getElementById(elementId);
            // Si el valor está vacío, mostrar el placeholder
            originalElement.innerHTML = value || placeholder;
        }

        function triggerFileInput() {
            document.getElementById('fileInput').click();
        }

        function handleFileSelect(event) {
            var input = event.target;
            var preview = document.getElementById('logoPreview');
            var placeholder = document.getElementById('logoPlaceholder');

            var reader = new FileReader();
            reader.onload = function () {
                preview.src = reader.result;
                preview.style.display = 'block';
                placeholder.style.display = 'none';
            };
            reader.readAsDataURL(input.files[0]);
        }


        const table = new DataTable('#invoiceTable', {
            dom: 'Bfrtip',
            order: [[1, 'asc']],
            //no search bar and no pagination
            searching: false,
            paging: false,
            //no info of the table
            info: false,
            //oculta la columna id
            columnDefs: [
                {
                    targets: [0],
                    visible: false,
                    searchable: false
                }
            ],
        });
        // Activate an inline edit on click of a table cell
        table.on('click', 'tbody td:not(:first-child)', function (e) {
            editor.inline(this);
        });

        // Función para agregar una nueva fila editable a la tabla
        function addInvoiceItem() {
            table.row.add(['0', 'Nuevo Producto', '1', '0.00', '<button class="btn btn-danger" onclick="removeInvoiceItem(this)">Eliminar</button>']).draw();
        }

        //detecta si es mobile o no, si es mobile le quita a invoiceTable la clase d-block si no, se la agrega
        if (window.matchMedia("(max-width: 767px)").matches) {
            document.getElementById("invoiceTable").classList.add("d-block");
        } else {
            document.getElementById("invoiceTable").classList.remove("d-block");
        }
        //detecta la columna donde di doble click
        table.on('dblclick', 'tbody td', function (e) {
            var colIdx = table.cell(this).index().column;
            var rowIdx = table.cell(this).index().row;
            var data = table.cell(rowIdx, colIdx).data();
            //vuelve en un input la celda donde di doble click y la guarda en una variable
            var input = table.cell(rowIdx, colIdx).data(`<input type="text" id="input${rowIdx}${colIdx}" value="${data}" onblur="makeNonEditable('input${rowIdx}${colIdx}', '${data}')">`);
            //selecciona el input creado
            document.getElementById(`input${rowIdx}${colIdx}`).focus();
            //detecta cuando se presiona enter en el input
            document.getElementById(`input${rowIdx}${colIdx}`).addEventListener("keyup", function (event) {
                if (event.keyCode === 13) {
                    // Cancel the default action, if needed
                    event.preventDefault();
                    // Trigger the button element with a click
                    document.getElementById(`input${rowIdx}${colIdx}`).blur();
                }
            });
            //al dar click fuera del input lo vuelve a convertir en texto
            document.getElementById(`input${rowIdx}${colIdx}`).addEventListener("blur", function (event) {
                var input = document.getElementById(`input${rowIdx}${colIdx}`);
                var value = input.value;
                var originalElement = table.cell(rowIdx, colIdx).data(value);
            });
            //detecta al dar enter o salir del input para ejecutar la funcion calcular
            document.getElementById(`input${rowIdx}${colIdx}`).addEventListener("blur", function (event) {
                calcular();
            });


        });

        function calcular() {
            var datosTabla = table.rows().data();
            var subtotal = 0;
            var taxes = 0;
            var discount = 0;
            var total = 0;
            for (var i = 0; i < datosTabla.length; i++) {
                var cantidad = datosTabla[i][2];
                var totalProducto = datosTabla[i][3];
                //si totalProducto aun tiene $ se lo quita
                if (totalProducto.includes('$')) {
                    totalProducto = totalProducto.replace('$', '');
                }
                subtotal = subtotal + parseFloat(totalProducto);
            }
            taxes = subtotal * 0.16;
            total = subtotal + taxes - discount;
            document.getElementById("subtotal_factura").innerHTML = subtotal.toFixed(2);
            document.getElementById("impuesto_factura").innerHTML = taxes.toFixed(2);
            document.getElementById("total_factura").innerHTML = total.toFixed(2);
        }


        function removeInvoiceItem(button) {
            table.row($(button).parents('tr')).remove().draw();
            calcular();
        }

        function saveInvoice() {
            $('#modalConfirmarEnvio').modal('show');
        }

        function sendInvoice() {


            //obtiene todos los datos de los elementos con la clase editable
            var datos = document.getElementsByClassName("editable");
            //crea un objeto con los datos obtenidos
            var datosObjeto = {};
            for (var i = 0; i < datos.length; i++) {
                datosObjeto[datos[i].id] = datos[i].innerHTML;
            }
            //valida que nombre_archivo no sea vacio
            if ($('#nombre_archivo').val() == '') {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'El nombre del archivo es obligatorio!',
                })
                return;
            }
            datosObjeto['nombre_invoice'] = $('#nombre_archivo').val();
            if (datosObjeto['fecha_factura'] == '') {
                datosObjeto['fecha_factura'] = $('#fecha_factura').val();
            }
            if (datosObjeto['fecha_vencimiento'] == '') {
                datosObjeto['fecha_vencimiento'] = $('#fecha_vencimiento').val();
            }
            if (datosObjeto['nombre_compania'] == 'Nombre de la Compañía' || datosObjeto['nombre_compania'] == '' || datosObjeto['nombre_compania'].includes('Nombre de la Compañía') || datosObjeto['nombre_compania'].includes('Nombre de la Compañía')) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'El nombre de la compañia es obligatorio!',
                })
                return;
            }
            if (datosObjeto['url_sitio_web'] == 'URL del Sitio: www.tuempresa.com' || datosObjeto['url_sitio_web'] == '' || datosObjeto['url_sitio_web'].includes('URL del Sitio: www.tuempresa.com') || datosObjeto['url_sitio_web'].includes('URL del Sitio: www.tuempresa.com')) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'La URL del sitio es obligatoria!',
                })
                return;
            }
            if (datosObjeto['numero_compania'] == 'Número de Teléfono: 123-456-789' || datosObjeto['numero_compania'] == '' || datosObjeto['numero_compania'].includes('Número de Teléfono: 123-456-789') || datosObjeto['numero_compania'].includes('Número de Teléfono: 123-456-789')) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'El número de teléfono es obligatorio!',
                })
                return;
            }
            if (datosObjeto['direccion'] == 'Dirección: Dirección de la Compañía' || datosObjeto['direccion'] == '' || datosObjeto['direccion'].includes('Dirección: Dirección de la Compañía') || datosObjeto['direccion'].includes('Dirección: Dirección de la Compañía')) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'La dirección es obligatoria!',
                })
                return;
            }
            if (datosObjeto['correo_electronico'] == 'Correo: info@tuempresa.com' || datosObjeto['correo_electronico'] == '' || datosObjeto['correo_electronico'].includes('Correo: info@tuempresa.com') || datosObjeto['correo_electronico'].includes('Correo: info@tuempresa.com')) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'El correo es obligatorio!',
                })
                return;
            }
            if (datosObjeto['nombre_cliente'] == 'Nombre del Cliente' || datosObjeto['nombre_cliente'] == '' || datosObjeto['nombre_cliente'].includes('Nombre del Cliente') || datosObjeto['nombre_cliente'].includes('Nombre del Cliente')) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'El nombre del cliente es obligatorio!',
                })
                return;
            }
            if (datosObjeto['correo_cliente'] == 'Correo del Cliente' || datosObjeto['correo_cliente'] == '' || datosObjeto['correo_cliente'].includes('Correo del Cliente') || datosObjeto['correo_cliente'].includes('Correo del Cliente')) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'El correo del cliente es obligatorio!',
                })
                return;
            }
            if (datosObjeto['numero_factura'] == '###' || datosObjeto['numero_factura'] == '' || datosObjeto['numero_factura'].includes('###') || datosObjeto['numero_factura'].includes('###')) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'El número de factura es obligatorio!',
                })
                return;
            }
            //fechas no pueden ser vacias ni fecha de vencimiento menor a fecha de creacion
            if (datosObjeto['fecha_factura'] == '' || datosObjeto['fecha_vencimiento'] == '') {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Las fechas son obligatorias!',
                })
                return;
            }
            if (datosObjeto['fecha_factura'] > datosObjeto['fecha_vencimiento']) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'La fecha de vencimiento no puede ser menor a la fecha de creación!',
                })
                return;
            }
            //fileInput
            var logo = document.getElementById('fileInput').files[0];
            //valida que el logo no sea vacio y que no este indefinido
            if (logo != undefined && logo != '') {
                datosObjeto['imagen'] = logo;
            }
            datosObjeto['id_usuario'] = $('#id_usuario').val();
            var datosTabla = table.rows().data();
            //debe de existir al menos un producto
            if (datosTabla.length == 0) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Debe de existir al menos un producto en la factura!',
                })
                return;
            }
            //crea un arreglo con los datos de la tabla
            var datosTablaArreglo = [];
            for (var i = 0; i < datosTabla.length; i++) {
                if (datosTabla[i][3].includes('$')) {
                    datosTabla[i][3] = datosTabla[i][3].replace('$', '');
                }
                datosTablaArreglo.push({
                    'id': datosTabla[i][0],
                    'nombre_producto': datosTabla[i][1],
                    'cantidad': datosTabla[i][2],
                    'precio': datosTabla[i][3],
                });
            }
            //crea un objeto con los datos de la tabla
            var datosTablaObjeto = {};
            for (var i = 0; i < datosTablaArreglo.length; i++) {
                datosTablaObjeto[i] = datosTablaArreglo[i];
            }
            //crea un objeto con los datos del objeto de la tabla y el objeto de los datos
            var datosObjetoFinal = {};
            datosObjetoFinal = datosObjeto;
            datosObjetoFinal['detalle'] = datosTablaObjeto;
            //envia los datos al servidor con animacion de carga con sweetalert2
            Swal.fire({
                title: 'Guardando Invoice',
                html: 'Por favor espere',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading()
                },
            });

            var formData = new FormData();
            //los datos son tal cual los datosObjetoFinal clave valor
            for (var i = 0; i < Object.keys(datosObjetoFinal).length; i++) {
                formData.append(Object.keys(datosObjetoFinal)[i], Object.values(datosObjetoFinal)[i]);
            }
            formData.delete('detalle');
            formData.append('detalle', JSON.stringify(datosObjetoFinal['detalle']));
            if (logo != undefined && logo != '') {
                formData.append('imagen', logo);
            }
            formData.append('comentario_factura', $('#comentario_factura').val());
            formData.append('id', $('#id_invoice').val());
            $.ajax({
                url: '/api/invoice/updateInvoice',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function (data) {
                    if (data.status == 'success') {
                        Swal.close();
                        Swal.fire({
                            icon: 'success',
                            title: 'Invoice Modificado!',
                            showConfirmButton: false,
                            timer: 1500
                        })
                        //redirecciona a la pagina de inicio
                        window.location.href = "/inicio";
                    } else {
                        Swal.close();
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Ocurrio un error! ' + data.message,
                        })
                    }
                    //redirecciona a la pagina de inicio
                    // window.location.href = "/inicio";

                },
                error: function (data) {
                    //ciera el sweetalert2 de carga
                    console.log(data);
                    Swal.close();
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Ocurrio un error!',
                    })
                }
            });
        }
    </script>
@endsection
