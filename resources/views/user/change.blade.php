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
                    <h4 style="color: #050108"><label class="text-start">Cambios CANAWIL</label></h4>
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
                    <form action="/newpassword" method="POST" id="view" name="view">
                        @csrf
                        @foreach ($users as $user)
                        <input type="hidden" id="user_id" name="user_id" value="{{ $user->id }}">
                        <input type="hidden" id="toaction" name="toaction" value="newpassword">
                        <input type="hidden" id="numrandom" name="numrandom" value="{{$numrandom}}">
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header"><i class="fas fa-check"></i>
                                        <b> Pregunta de Seguridad</b>
                                    </div>
                                    <div class="card-body">
                                        <div class='row'>
                                            <div class="col-md-6 form-group">
                                                <label for="question" class="form-label"><b>Pregunta de Seguridad:</b></label>
                                                @if ($numrandom >= 100 & $numrandom <= 300)
                                                    <input disabled type="text" class="form-control" id="question" name="question" onkeypress="quitaMensaje()" maxlength="191" value="{{ $user->question1 }}" placeholder="Pregunta de Seguridad">
                                                @endif
                                                @if ($numrandom > 300 & $numrandom <= 600)
                                                    <input disabled type="text" class="form-control" id="question" name="question" onkeypress="quitaMensaje()" maxlength="191" value="{{ $user->question2 }}" placeholder="Pregunta de Seguridad">
                                                @endif
                                                @if ($numrandom > 600 & $numrandom <= 900)
                                                    <input disabled type="text" class="form-control" id="question" name="question" onkeypress="quitaMensaje()" maxlength="191" value="{{ $user->question3 }}" placeholder="Pregunta de Seguridad">
                                                @endif
                                            </div>
                                            <div class="col-md-6 form-group">
                                                <label for="answer" class="form-label"><b>Respuesta (*):</b></label>
                                                <input type="text" class="form-control" id="answer" name="answer" onkeypress="quitaMensaje()" maxlength="191" value="{{ old('answer') }}" placeholder="Respuesta a la pregunta de seguridad" tabindex="1" autofocus>
                                                <div id="answer_empty" class="talert" style='display: none;'>
                                                    <p class="text-danger">La respuesta es requerida</p>
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
                        @endforeach
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
                var xanswer = document.getElementById("answer").value;
                if  (xanswer.length < 1){
                    xseguir = false;
                    document.getElementById("answer_empty").style.display = "block";
                }
                if (xseguir){
                    document.view.submit();
                }
            }
        </script>
    </body>

</html>
