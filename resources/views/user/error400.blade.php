<!doctype html>
<html lang="es">
    <head>
        <!-- Required meta tags -->
        <title>CANAWIL | Error 400</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">

        <link href="https://fonts.googleapis.com/css2?family=Mulish:wght@300;900&display=swap" rel="stylesheet">
        <link rel="preconnect" href="https://fonts.gstatic.com">

        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/normalize.css@8.0.1/normalize.min.css">
    	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/glider-js@1.7.3/glider.min.css">

        <link rel="Shortcut Icon" type="image/x-icon" href="/favicon.ico" />
    </head>

    <body>
        <nav class="navbar navbar-expand-lg navbar-light fixed-top">
            <div class="container-fluid">
                <a href="#">
                    <img src="/checkicon.png" alt="" width="60" height="55">
                    <h4 style="color: #040207"><label class="text-start">CANAWIL</label></h4>
                </a>
            </div>
        </nav>

        <br><br><br>

        <div class="container-fluid">
            <br><br><br><br><br>
            <div class="row">
                <div class="col-md-12 form-group shadow-lg p-3 mb-5 bg-body rounded">
                    <h1 class="text-center">Error 400: Respuesta de Seguridad inválida</h1>
                </div>
                <div class="col-md-3 form-group">
                </div>
                <div class="col-md-6 form-group">
                    <br>
                    <div class="row">
                        <h3 class="text-center" style="color:rgb(232, 23, 23)">Las Respuesta que suministraste no coincide con nuestra información en la Base de Datos.</h3>
                    </div>
                    <br>
                    <button type="button" class="btn btn-danger" onclick="validar()">Aceptar <i class="fa fa-arrow-circle-left"></i></button>
                </div>
            </div>
        </div>
        <br><br>

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
        <script src="https://kit.fontawesome.com/2c36e9b7b1.js" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/glider-js@1.7.3/glider.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <script>
            function validar(){

                var formulario = document.createElement('form');
                formulario.action='/';
                formulario.method='GET';

                document.body.appendChild(formulario);
                formulario.submit();

            }
        </script>
    </body>

</html>
