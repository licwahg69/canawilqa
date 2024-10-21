<!doctype html>
<html lang="es">
    <head>
        <!-- Required meta tags -->
        <title>CANAWIL | Olvide la contraseña</title>
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
                    <h4 style="color: #050505"><label class="text-start">Cambios CANAWIL</label></h4>
                </a>
            </div>
        </nav>

        <br><br><br>

        <div class="container-fluid">
            <br><br><br><br>
            <div class="row">
                <div class="col-md-12 form-group shadow-lg p-3 mb-5 bg-body rounded">
                    <h1 class="text-center">Danos tus datos para encontrarte</h1>
                </div>
                <div class="col-md-3 form-group">
                </div>
                <div class="col-md-6 form-group">
                    <form action="/userforgot" method="POST" id="view" name="view">
                        @csrf
                        <input type="hidden" id="toaction" name="toaction" value="userforgot">
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header"><i class="fas fa-check"></i>
                                        <b> Identificación en CANAWIL</b>
                                    </div>
                                    <div class="card-body">
                                        <div class='row'>
                                            <div class="col-md-12 form-group">
                                                <label for="email" class="form-label"><b>Email (*):</b></label>
                                                <input class="form-control" type="email" id="email" name="email" maxlength=191 onkeydown="quitaMensaje()" onKeyPress="solocorreos(event)" onblur="validacorreos(this.id,this.value)" value="{{ old('email') }}" placeholder="Email del usuario" tabindex="2">
                                                <div id="email_error" class="talert" style='display: none;'>
                                                    <p class="text-danger">Por favor indique una dirección de correo válida</p>
                                                </div>
                                                <div id="email_empty" class="talert" style='display: none;'>
                                                    <p class="text-danger">Debe introducir una dirección de correo válida</p>
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

            function solocorreos(event){
                key = event.keyCode || event.which;
                tecla = String.fromCharCode(key).toLowerCase();

                letras = " áéíóúabcdefghijklmnñopqrstuvwxyz1234567890-_.@";

                especiales = [8,37,39,46,116];
                tecla_especial = false;
                for(var i in especiales){
                    if(key == especiales[i]){
                        tecla_especial = true;
                        break;
                        }
                    }

                if(letras.indexOf(tecla) ==-1 && (tecla_especial == false)){

                    event.preventDefault();
                }
            }

            function validacorreos(xid,xvalor){
                var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
                if (xvalor.length > 0 && !re.test(xvalor)){
                    document.getElementById(xid).value = "";
                    document.getElementById(xid+"_error").style.display = "block";
                    document.getElementById(xid).focus();
                }
            }

            function validar(){
                var xseguir = true;
                var xemail = document.getElementById("email").value;
                if  (xemail.length < 1){
                    xseguir = false;
                    document.getElementById("email_empty").style.display = "block";
                }
                if (xseguir){
                    document.view.submit();
                }
            }
        </script>
    </body>

</html>
