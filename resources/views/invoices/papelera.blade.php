@extends('layouts._blankNav')
@section('titulo',"Inicio")
@section('css')
@endsection
@section('contenido')
    <div class="container-fluid"><h3 class="text-dark mb-4">Papelera</h3>
        <div class="card shadow">
            <div class="card-header py-3"><p class="text-primary m-0 fw-bold">Invoices</p></div>
            <div class="card-body">
                <div class="row">
                </div>
                <div class="table-responsive table table-hover mt-2" id="dataTable" role="grid"
                     aria-describedby="dataTable_info">
                    <table class="table table-striped table-hover my-0" id="dataTableInvoice">
                        <thead>
                        <tr>
                            <th>Nombre Invoice</th>
                            <th>Fecha de Creaci&oacute;n</th>
                            <th>Fecha de Eliminaci&oacute;n</th>
                            <th>Acciones</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script>
        $(document).ready(function () {
            $.ajax({
                url: '/api/invoice/getInvoices',
                type: 'get',
                async: true,
                //al collums orderables without the first column
                order: [[1, 'asc']],
                dataType: 'json',
                data: {
                    'id_usuario': $('#id_usuario').val(),
                    'activo': '0'
                },
                success: function (data) {
                    datos = data.data;
                    getInvoices(datos);
                },
                error: function (data) {
                    console.log(data);
                }
            });

        });

        function getInvoices(data) {
            console.log('data')
            console.log(data)
            var tabla = $('#dataTableInvoice').DataTable({
                processing: true,
                searching: false,
                data: data,
                columns: [
                    {data: 'nombre_invoice'},
                    {data: 'created_at'},
                    {data: 'updated_at'},
                    {
                        data: null,
                        render: function (data, type, row) {
                            var reactiveButton = '<a type="button" onclick="reactiveInvoice(this)" class="btn btn-success" data-id="' + data.id + '"><i class="fas fa-redo"></i></a>';
                            var deleteButton = '<button class="delete-button btn btn-danger" data-id="' + data.id + '" onclick="deleteInvoice(this)"><i class="fas fa-trash"></i></button>';
                            var viewButton = '<a type="button" href="/invoice/generatePdf/' + data.id + '" class="btn btn-info"><i class="fas fa-eye"></i></a>';

                            return reactiveButton + ' ' + deleteButton + ' ' + viewButton;
                        }
                    }

                ],
            });
        }

        function deleteInvoice(e) {
            var id = $(e).data('id');
            Swal.fire({
                title: '¿Estás seguro?',
                text: "¡No podrás revertir esto!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, bórralo',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/api/invoice/deleteInvoicePermanently/' + id,
                        type: 'delete',
                        async: true,
                        dataType: 'json',
                        success: function (data) {
                            Swal.fire(
                                '¡Eliminado!',
                                'El invoice ha sido eliminado.',
                                'success'
                            )
                            location.reload();
                        },
                        error: function (data) {
                            console.log(data);
                        }
                    });
                }
            })
        }
        function reactiveInvoice(e) {
            var id = $(e).data('id');
            Swal.fire({
                title: '¿Estás seguro?',
                text: "¡Podras verlo en tus invoices!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, reactivarlo',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/api/invoice/reactivate/' + id,
                        type: 'put',
                        async: true,
                        dataType: 'json',
                        success: function (data) {
                            Swal.fire(
                                '¡Reactivado!',
                                'El invoice ha sido reactivado.',
                                'success'
                            )
                            location.reload();
                        },
                        error: function (data) {
                            console.log(data);
                        }
                    });
                }
            })
        }
    </script>
@endsection
