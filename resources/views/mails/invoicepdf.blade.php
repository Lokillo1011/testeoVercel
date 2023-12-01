<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Archivo Creado</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-gH2yIVqQkTU6TPAhTTAU0U8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3ElQSqhD" crossorigin="anonymous">
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>Hola {{ $user->name }}</h1>
            <p>Su archivo ha sido creado</p>
            <p>Saludos</p>
            <p>Equipo de {{ config('app.name') }}</p>
        </div>
    </div>
</div>
</body>
</html>
