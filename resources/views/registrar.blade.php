<!DOCTYPE html>
<html data-bs-theme="light" lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Registrar - Hotspot</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css?h=97380e22c8933e9aa79cbc2390b9f15a">
    <link rel="stylesheet" href="{{asset('assets/css/styles.min.css')}}">
</head>
<body class="bg-gradient-primary" style="background: rgb(254,119,7);">
<div class="container">
    <div class="card shadow-lg o-hidden border-0 my-5">
        <div class="card-body p-0">
            <div class="row">
                <div class="col-lg-5 d-none d-lg-flex">
                    <div class="flex-grow-1 bg-register-image"
                         style="background-image: url(&quot;assets/img/hubspot5453.logowik.com.webp?h=504c9403ea2df4c9ef45cca22fa6c0bc&quot;)"></div>
                </div>
                <div class="col-lg-7">
                    <div class="p-5">
                        <div class="text-center"><h4 class="text-dark mb-4">Crear Cuenta</h4></div>
                        <form class="user">
                            <div class="row mb-3">
                                <div class="col mb-3 mb-sm-0"><input class="form-control form-control-user" type="text"
                                                                     id="nombre" placeholder="Nombre"
                                                                     name="first_name"></div>
                            </div>
                            <div class="mb-3"><input class="form-control form-control-user" type="email"
                                                     id="correo" aria-describedby="emailHelp"
                                                     placeholder="Correo" name="email"></div>
                            <div class="row mb-3">
                                <div class="col-sm-6 mb-3 mb-sm-0"><input class="form-control form-control-user"
                                                                          type="password" id="contrasenia"
                                                                          placeholder="contrasenia" name="password">
                                </div>
                                <div class="col-sm-6"><input class="form-control form-control-user" type="password"
                                                             id="exampleRepeatPasswordInput"
                                                             placeholder="Confirmar Contrasenia"
                                                             name="contrasenia_confirmation">
                                </div>
                            </div>
                            <a class="btn btn-primary d-block btn-user w-100" type="button" onclick="crearCuenta()"
                               style="background: rgb(255,118,7);">Registrar
                            </a>
                            <hr>
                            <hr>
                        </form>
                        {{--                        //registrar
                        Route::get('/registrar', function () {
                            return view('registrar');
                        });--}}
                        <div class="text-center"><a class="small" href="/login">
                                Iniciar Sesión</a></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{asset('assets/js/script.min.js')}}"></script>
<script>
    function crearCuenta() {
        let nombre = document.getElementById('nombre').value;
        let correo = document.getElementById('correo').value;
        let contrasenia = document.getElementById('contrasenia').value;
        let contrasenia_confirmation = document.getElementById('exampleRepeatPasswordInput').value;
        //validar que los campos no esten vacios y que sean validos
        if (nombre === '' || correo === '' || contrasenia === '' || contrasenia_confirmation === '') {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Todos los campos son obligatorios!',
            })
            return;
        }
        //validar que el correo sea valido
        if (!validateEmail(correo)) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'El correo no es valido!',
            })
            return;
        }
        //validar que las contrasenias sean iguales
        if (contrasenia !== contrasenia_confirmation) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Las contraseñas no coinciden!',
            })
            return;
        }
        //valida que la contrasenia tenga al menos 8 caracteres
        if (contrasenia.length < 8) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'La contraseña debe tener al menos 8 caracteres!',
            })
            return;
        }
        //enviar los datos al servidor
        $.ajax({
            url: '/api/register',
            type: 'POST',
            data: {
                nombre: nombre,
                correo: correo,
                contrasenia: contrasenia,
                contrasenia_confirmation: contrasenia_confirmation,
                _token: '{{csrf_token()}}'
            },
            success: function (response) {
                Swal.fire({
                    icon: 'success',
                    title: 'Cuenta creada correctamente!',
                    showConfirmButton: false,
                    timer: 1500
                })
                setTimeout(function () {
                    window.location.href = "/login";
                }, 1500);
            },
            error: function (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Ocurrio un error!',
                })
            }
        });
    }

    function validateEmail(email) {
        let re = /\S+@\S+\.\S+/;
        return re.test(email);
    }

</script>
</body>
</html>
