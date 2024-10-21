<!doctype html>
<html lang="es">
    <head>
        <!-- Required meta tags -->
        <title>CANAWIL | Autenticación en CANAWIL</title>
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
                    <h4 style="color: #040305"><label class="text-start">Cambios CANAWIL</label></h4>
                </a>
            </div>
        </nav>

        <br><br><br>

        <div class="container-fluid">
            <br><br><br><br>
            <div class="row">
                <div class="col-md-12 form-group shadow-lg p-3 mb-5 bg-body rounded">
                    <h1 class="text-center">Autenticación en CANAWIL</h1>
                </div>
                <div class="col-md-12 form-group">
                    <form action="/savepassword" method="POST" id="view" name="view">
                        @csrf
                        <input type="hidden" id="toaction" name="toaction" value="savepassword">
                        <input type="hidden" id="user_id" name="user_id" value="{{$user_id}}">
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header"><i class="fas fa-fw fa-lock"></i>
                                        <b> Cambio de contraseña</b>
                                    </div>
                                    <div class="card-body">
                                        <div class='row'>
                                            <div class="col-md-6 form-group">
                                                <label for="password" class="form-label"><b>Nueva Contraseña (*):</b></label>
                                                <input class="form-control height" type="password" id="password" name="password" placeholder="Nueva contraseña del usuario"
                                                    maxlength=191 value="" onkeypress="quitaMensaje()" tabindex="2">
                                                <div id="password_empty" class="talert" style='display: none;'>
                                                    <p class="text-danger">La contraseña es requerida</p>
                                                </div>
                                                <div id="password_error" class="talert" style='display: none;'>
                                                    <p class="text-danger">Las contraseñas no coinciden</p>
                                                </div>
                                            </div>
                                            <div class="col-md-6 form-group">
                                                <label for="repassword" class="form-label"><b>Repita la Contraseña (*):</b></label>
                                                <input class="form-control height" type="password" id="repassword" name="repassword" placeholder="Repetir Contraseña"
                                                    maxlength=191 value="" onkeypress="quitaMensaje()" tabindex="3">
                                                <div id="repassword_empty" class="talert" style='display: none;'>
                                                    <p class="text-danger">Debe repetir la contraseña</p>
                                                </div>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="col-md-12 form-group">
                                            <b>(*) Campos obligatorios</b>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <br>
                    <button type="button" class="btn btn-primary" onclick="validar()">Enviar <i class="fa fa-sign-in-alt"></i></button>
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
            function quitaMensaje(){
                $(".talert").css("display", "none");
            }

            function validar(){
                var xseguir = true;
                var xpassword = document.getElementById("password").value;
                if  (xpassword.length < 1){
                    xseguir = false;
                    document.getElementById("password_empty").style.display = "block";
                }
                var xrepassword = document.getElementById("repassword").value;
                if  (xrepassword.length < 1){
                    xseguir = false;
                    document.getElementById("repassword_empty").style.display = "block";
                }
                if  (xpassword != xrepassword){
                    xseguir = false;
                    document.getElementById("password").value = "";
                    document.getElementById("repassword").value = "";
                    document.getElementById("password_error").style.display = "block";
                }
                if (xseguir){
                    document.view.submit();
                }
            }
        </script>
    </body>

</html>
