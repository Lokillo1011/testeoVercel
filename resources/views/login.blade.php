<!DOCTYPE html>
<html data-bs-theme="light" lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Login - Hotspot</title>
    <link rel="stylesheet" href="{{asset('assets/bootstrap/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/styles.min.css')}}">
</head>
<body class="bg-gradient-primary" style="background: rgb(255,118,7);">
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-9 col-lg-12 col-xl-10">
            <div class="card shadow-lg o-hidden border-0 my-5">
                <div class="card-body p-0">
                    <div class="row">
                        <div class="col-lg-6 d-none d-lg-flex">
                            <div class="flex-grow-1 bg-login-image"
                                 style="background-image: url(&quot;assets/img/hubspot5453.logowik.com.webp?h=504c9403ea2df4c9ef45cca22fa6c0bc&quot;)"></div>
                        </div>
                        <div class="col-lg-6">
                            <div class="p-5">
                                <div class="text-center"><h4 class="text-dark mb-4">Bienvenido!</h4></div>
                                @if(isset($errors) && $errors != [] && $errors != null && $errors != '' && $errors != '[]' && $errors != 'null' && $errors != '')
                                    <div class="alert alert-danger" role="alert">
                                        {{$errors}}
                                    </div>
                                @endif
                                <form class="user" action="/iniciar_sesion" method="get">
                                    <div class="mb-3"><input class="form-control form-control-user" type="email"
                                                             id="correo" aria-describedby="emailHelp"
                                                             placeholder="Correo" name="correo"></div>
                                    <div class="mb-3"><input class="form-control form-control-user" type="password"
                                                             id="contrasenia" placeholder="Contrasenia"
                                                             name="contrasenia"></div>
                                    <div class="mb-3"></div>
                                    <button class="btn btn-primary d-block btn-user w-100" type="submit"
                                            style="background: rgb(255,118,7);">Acceder
                                    </button>
                                    <hr>
                                    <hr>
                                </form>
                                <div class="text-center"><a class="small" href="/registrar">
                                        Crear Cuenta</a></div>
                            </div>
                        </div>
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
</body>
</html>
