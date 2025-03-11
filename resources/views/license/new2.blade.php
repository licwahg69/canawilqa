@extends('adminlte::page')

@section('title', 'Enviar Licencia')

@section('content_header')
    <h1 class="m-0 text-primary text-center"><b>Enviar licencias a usuarios de CANAWIL</b></h1>
@stop

@section('content')
<form action="/license" method="POST" id="view" name="view">
    @csrf
    <input type="hidden" id="toaction" name="toaction" value="create2">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header"><i class="fas fa-envelope"></i>
                    <b> Datos del Usuario</b>
                </div>
                <div class="card-body">
                    <div class='row'>
                        <div class="col-md-2 form-group">
                            <label for="role" class="form-label"><b>Licencia (*):</b></label>
                            <select id="role" name="role" class="form-control" onchange="quitaMensaje()">
                                <option value="">Seleccionar</option>
                                <option value="ALI">Aliado Comercial</option>
                                <option value="USU">Usuario</option>
                            </select>
                            <div id="role_error" class="talert" style='display: none;'>
                                <p class="text-danger">El tipo de Licencia es requerido</p>
                            </div>
                        </div>
                        <div class="col-md-2 form-group">
                            <label for="gender" class="form-label"><b>Sexo (*):</b></label>
                            <select id="gender" name="gender" class="form-control" onchange="quitaMensaje()">
                                <option value="">Seleccionar</option>
                                <option value="MAS">Masculino</option>
                                <option value="FEM">Femenino</option>
                            </select>
                            <div id="gender_error" class="talert" style='display: none;'>
                                <p class="text-danger">El sexo es requerido</p>
                            </div>
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="name" class="form-label"><b>Nombre Completo (*):</b></label>
                            <input type="text" class="form-control" id="name" name="name" maxlength="191" onkeypress="quitaMensaje()" value="{{ old('name') }}" placeholder="Nombre Completo">
                            <div id="name_error" class="talert" style='display: none;'>
                                <p class="text-danger">El Nombre Completo es requerido</p>
                            </div>
                        </div>
                        <div class="col-md-4 form-group">
                            <b><label for="email" class="form-label">Email (*):</label></b>
                            <input type="email" class="form-control" id="email" name="email" maxlength="191" onkeypress="quitaMensaje(), solocorreos(event)" onblur="validacorreos(this.id,this.value)" value="{{ old('email') }}" placeholder="Email">
                            <div id="email_error" class="talert" style='display: none;'>
                                <p class="text-danger">Debe colocar una dirección de Email válida</p>
                            </div>
                            <div id="email_empty" class="talert" style='display: none;'>
                                <p class="text-danger">El Email es requerido</p>
                            </div>
                            <div id="email_found" class="talert" style='display: none;'>
                                <p class="text-danger">Ya existe este Email en el Sistema</p>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="col-md-12 form-group">
                        <b>(*) Campos obligatorios</b>
                    </div>
                    <div class="row">
                        <div class="col-md-2 form-group text-center">
                            <button type="button" onclick="validar()" class="btn btn-success btn-block" tabindex="6">Enviar  <i class="far fa-paper-plane"></i></button>
                        </div>
                        <div class="col-md-2 form-group text-center">
                            <a href="/home" class="btn btn-secondary btn-block" tabindex="7">Cancelar  <i class="fa fa-arrow-circle-left"></i></a>
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
<div class="float-right d-sm-inline">
    <label class="text-primary">© {{ date_format(date_create(date("Y")),"Y") }} Cambios CANAWIL</label>, todos los derechos reservados.
</div>
@stop

@section('js')
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

    function getEmail(xemail) {
        fetch(`/email/${xemail}`)
            .then(response => response.json())
            .then(jsondata => showEmail(jsondata))
    }

    function showEmail(jsondata){
        let response = jsondata.response;

        if (response == 'S'){
            document.getElementById("email_found").style.display = "block";
        } else {
            document.view.submit();
        }
    }

    function validar(){
        var xseguir = true;
        var xrole = document.getElementById("role").value;
        if (xrole.length < 1){
            xseguir = false;
            document.getElementById("role_error").style.display = "block";
        }
        var xgender = document.getElementById("gender").value;
        if (xgender.length < 1){
            xseguir = false;
            document.getElementById("gender_error").style.display = "block";
        }
        var xname = document.getElementById("name").value;
        if (xname.length < 1){
            xseguir = false;
            document.getElementById("name_error").style.display = "block";
        }
        var xemail = document.getElementById("email").value;
        if (xemail.length < 1){
            xseguir = false;
            document.getElementById("email_empty").style.display = "block";
        }
        if (xseguir){
            getEmail(xemail);
        }
    }
</script>
@stop
