@extends('adminlte::page')

@section('title', 'Cambio de Password')

@section('content_header')
    <h1 class="m-0 text-primary text-center"><b>Cambiar mi Contraseña: </b><label class="text-dark">{{$users->name}}</label></h1>
@stop

@section('content')
<form action="/user" method="POST" id="view" name="view">
    @csrf
    <input type="hidden" id="user_id" name="user_id" value="{{$users->id}}">
    <input type="hidden" id="toaction" name="toaction" value="password">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header"><i class="fas fa-fw fa-lock"></i>
                    <b> Cambio de contraseña</b>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label for="question">Pregunta de Seguridad:</label>
                            @if ($numrandom >= 100 & $numrandom <= 300)
                                <input disabled type="text" class="form-control" id="question" name="question" onkeypress="quitaMensaje()" maxlength="191" value="{{ $users->question1 }}" placeholder="Pregunta de Seguridad">
                            @endif
                            @if ($numrandom > 300 & $numrandom <= 600)
                                <input disabled type="text" class="form-control" id="question" name="question" onkeypress="quitaMensaje()" maxlength="191" value="{{ $users->question2 }}" placeholder="Pregunta de Seguridad">
                            @endif
                            @if ($numrandom > 600 & $numrandom <= 900)
                                <input disabled type="text" class="form-control" id="question" name="question" onkeypress="quitaMensaje()" maxlength="191" value="{{ $users->question3 }}" placeholder="Pregunta de Seguridad">
                            @endif
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="answer">Respuesta (*):</label>
                            <input type="text" class="form-control" id="answer" name="answer" onkeypress="quitaMensaje()" maxlength="191" value="{{ old('answer') }}" placeholder="Respuesta a la pregunta de seguridad" tabindex="1" autofocus>
                            <div id="answer_empty" class="talert" style='display: none;'>
                                <p class="text-danger">La respuesta es requerida</p>
                            </div>
                            <div id="answer_error" class="talert" style='display: none;'>
                                <p class="text-danger">Respuesta errada</p>
                            </div>
                            @if ($numrandom >= 100 & $numrandom <= 300)
                                <input type="hidden" id="realanswer" name="realanswer" value="{{ $users->answer1 }}">
                            @endif
                            @if ($numrandom > 300 & $numrandom <= 600)
                                <input type="hidden" id="realanswer" name="realanswer" value="{{ $users->answer2 }}">
                            @endif
                            @if ($numrandom > 600 & $numrandom <= 900)
                                <input type="hidden" id="realanswer" name="realanswer" value="{{ $users->answer3 }}">
                            @endif
                        </div>
                    </div>
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
                    <div class="col-md-12 form-group">
                        <b>(*) Campos obligatorios</b>
                    </div>
                    <div class="row">
                        <div class="col-md-2 form-group text-center">
                            <button type="button" onclick="validar()" class="btn btn-success btn-block" tabindex="4">Guardar  <i class="fa fa-save"></i></button>
                        </div>
                        <div class="col-md-2 form-group text-center">
                            <a href="/home" class="btn btn-secondary btn-block" tabindex="5">Cancelar  <i class="fa fa-arrow-circle-left"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
<form action="/force-logout" id="forcelogout" name="forcelogout" method="post">
    @csrf
    <input type="hidden" id="href" name="href" value="">
</form>
@stop

@section('footer')
<div id="copyrigth" class="float-right d-sm-inline">
    <label class="text-primary">© {{ date_format(date_create(date("Y")),"Y") }} Cambios CANAWIL</label>, todos los derechos reservados.
</div>
@stop

@section('js')
<script>
    function quitaMensaje(){
        $(".talert").css("display", "none");
    }

    function validar(){
        var xseguir = true;
        var xrealanswer = document.getElementById("realanswer").value;
        var xanswer = document.getElementById("answer").value;
        if  (xanswer.length < 1){
            xseguir = false;
            document.getElementById("answer_empty").style.display = "block";
        }
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
        if (xrealanswer != xanswer && xanswer.length > 0){
            xseguir = false;
            document.getElementById("answer_error").style.display = "block";
        } else {
            if  (xpassword != xrepassword){
                    xseguir = false;
                    document.getElementById("password").value = "";
                    document.getElementById("repassword").value = "";
                    document.getElementById("password_error").style.display = "block";
            }
        }
        if (xseguir){
            document.view.submit();
        }
    }
</script>

@stop
