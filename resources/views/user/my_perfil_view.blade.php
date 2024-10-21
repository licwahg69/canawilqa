@extends('adminlte::page')

@section('title', 'Editar perfil')

@section('content_header')
    <h1 class="m-0 text-primary text-center"><b>Editar perfil: </b><label class="text-dark">{{$users->name}}</label></h1>
@stop

@section('css')
<style type="text/css">
    .containerimghp {
        width: 210px;
        height: 200px;
    }

    .containerimghp img {
        width: 100%;
        height: 100%;
    }

    .file-select {
        position: relative;
        display: inline-block;
    }

    .file-select::before {
        background-color: #5678EF;
        color: white;
        display: flex;
        justify-content: center;
        align-items: center;
        border-radius: 3px;
        content: "Subir Foto";
        position: absolute;
        cursor: pointer;
        left: 0;
        right: 0;
        top: 0;
        bottom: 0;
    }

    .file-select input[type="file"] {
        opacity: 0;
        width: 250px;
        height: 50px;
        display: inline-block;
    }
</style>
@stop

@section('content')
@php
    $onlycellphone = str_replace($users->phone_code, '', $users->cellphone)
@endphp
<form action="/user" method="POST" id="view" name="view" enctype="multipart/form-data">
    @csrf
    <input type="hidden" id="user_id" name="user_id" value="{{$users->id}}">
    <input type="hidden" id="photo_path" name="photo_path" value="{{ $users->profile_photo_path }}">
    <input type="hidden" id="toaction" name="toaction" value="update2">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header"><i class="fa fa-id-card"></i>
                    <b> Datos del Perfil</b>
                </div>
                <div class="card-body">
                    <div class='row'>
                        <div class="col-md-12 form-group">
                            <div class="card" style='max-width:250px; max-height=240px;'>
                                <div class="card-header" style='max-width:250px; max-height=240px;'>
                                    <i class="fa fa-image"></i>
                                    <b>Foto</b>
                                </div>
                                <div class="card-body" style="width: 250px; height: 240px;">
                                    <div class="containerimghp" id="imgfoto" name="imgfoto">
                                        @if ($users->profile_photo_path == '')
                                            @if ($users->gender == 'MAS')
                                                <img style="width: 210px; height: 200px;" id="partnerfoto" src="/avatar_sinfotom.png" />
                                            @else
                                                <img style="width: 210px; height: 200px;" id="partnerfoto" src="/avatar_sinfotof.png" />
                                            @endif
                                        @else
                                            <img style="width: 210px; height: 200px;" id="partnerfoto" src="{{ $users->profile_photo_path }}" />
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class='row'>
                        @if ($users->role == 'ALI')
                            <div class="col-md-6 form-group">
                                <label for="name">Nombre Completo:</label>
                                <input disabled type="text" class="form-control" id="name" name="name" onkeypress="quitaMensaje()" maxlength="191" value="{{ old('name') ?? $users->name ?? old('name') }}" placeholder="Nombre del Usuario" tabindex="2">
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="comercial_name" class="form-label">Nombre Comercial:</label>
                                <input disabled class="form-control" type="text" id="comercial_name" name="comercial_name" maxlength=255 onkeydown="quitaMensaje()" value="{{ old('comercial_name') ?? $users->comercial_name ?? old('comercial_name') }}" placeholder="Nombre Comercial" tabindex="3">
                            </div>
                        @else
                            <div class="col-md-12 form-group">
                                <label for="name">Nombre Completo:</label>
                                <input disabled type="text" class="form-control" id="name" name="name" onkeypress="quitaMensaje()" maxlength="191" value="{{ old('name') ?? $users->name ?? old('name') }}" placeholder="Nombre del Usuario" tabindex="2">
                            </div>
                        @endif
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label for="email" class="form-label">Email:</label>
                            <input disabled class="form-control" type="email" id="email" name="email" maxlength=191 onkeydown="quitaMensaje()" onKeyPress="solocorreos(event)" onblur="validacorreos(this.id,this.value)" value="{{ old('email') ?? $users->email ?? old('email') }}" placeholder="Email del usuario" tabindex="3">
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="cellphone" class="form-label"><b>Celular (*):</b></label>
                            <div class="input-group mb-3">
                                <span class="input-group-text height"><i class="bi bi-whatsapp"></i></span>
                                <span class="input-group-text height">{{ $users->phone_code }}</span>
                                <input disabled class="form-control" type="text" id="cellphone" name="cellphone" placeholder="Número Celular" maxlength=15 onkeydown="quitaMensaje(this.id)" value="{{$onlycellphone}}" onKeyPress="solonumeros(event)">
                                <div id="cellphone_empty" style='display: none;'>
                                    <p class="text-danger">El Número Celular es requerido</p>
                                </div>
                                <div id="cellphone_error" style='display: none;'>
                                    <p class="text-danger">El primer número no puede ser 0</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2 form-group">
                            <label for="gender" class="form-label"><b>Sexo:</b> </label>
                            <select disabled id="gender" name="gender" class="select2 form-control" tabindex="6">
                                @if ($users->gender == 'MAS')
                                    <option value="MAS" selected>Masculino</option>
                                    <option value="FEM">Femenino</option>
                                @else
                                    <option value="MAS">Masculino</option>
                                    <option value="FEM" selected>Femenino</option>
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label for="document_description" class="form-label"><b>Tipo de Documento:</b></label>
                            <input disabled class="form-control height" type="text" id="document_description" name="document_description"
                                maxlength=15 value="{{ old('document_description') ?? $users->document_description ?? old('document_description') }}" tabindex="5">
                        </div>
                        <div class="col-md-3 form-group">
                            <label for="numdoc" class="form-label"><b>Número Documento ID:</b></label>
                            <input disabled class="form-control height" type="text" id="numdoc" name="numdoc"
                                maxlength=15 value="{{ old('numdoc') ?? $users->numdoc ?? old('numdoc') }}" tabindex="5">
                        </div>
                        <div class="col-md-5 form-group">
                            <label for="role_name" class="form-label"><b>Rol en el Sistema:</b></label>
                            <input disabled class="form-control height" type="text" id="role_name" name="role_name"
                                maxlength=15 value="{{ old('role_name') ?? $users->role_name ?? old('role_name') }}" tabindex="5">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label for="countryname" class="form-label"><b>País:</b></label>
                            <input disabled class="form-control height" type="text" id="countryname" name="countryname"
                                maxlength=80 value="{{ old('countryname') ?? $users->countryname ?? old('countryname') }}" tabindex="5">
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="name_location" class="form-label"><b>Estado o Departamento:</b></label>
                            <input disabled class="form-control height" type="text" id="name_location" name="name_location"
                                maxlength=60 value="{{ old('name_location') ?? $users->name_location ?? old('name_location') }}" tabindex="5">
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="name_town" class="form-label"><b>Ciudad o Municipio:</b></label>
                            <input disabled class="form-control height" type="text" id="name_town" name="name_town"
                                maxlength=60 value="{{ old('name_town') ?? $users->name_town ?? old('name_town') }}" tabindex="5">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 form-group">
                            <label for="address" class="form-label"><b>Dirección:</b></label>
                            <input disabled class="form-control height" type="text" id="address" name="address"
                                maxlength=300 value="{{ old('address') ?? $users->address ?? old('address') }}" tabindex="5">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-2 form-group">
                            <label for="credit" class="form-label"><b>Tiene Cédito:</b> </label>
                            <select id="credit" name="credit" class="select2 form-control">
                                @if ($users->credit == 'Y')
                                    <option value="Y" selected>SI</option>
                                    <option value="N">NO</option>
                                @else
                                    <option value="Y">SI</option>
                                    <option value="N" selected>NO</option>
                                @endif
                            </select>
                        </div>
                        <div class="col-md-3 form-group">
                            <label for="credit_limit">Limite del crédito:</label>
                            <input type="text" class="form-control" id="credit_limit" name="credit_limit" onkeydown="quitaMensaje()" onKeyPress="solonumeros(event)" value="{{ old('credit_limit') ?? $users->credit_limit ?? old('credit_limit') }}" placeholder="Limite del crédito">
                            <div id="credit_limit_error" class="talert" style='display: none;'>
                                <p class="text-danger">Debe indicar un limite de crédito</p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-2 form-group text-center">
                            <button type="button" onclick="validar()" class="btn btn-success btn-block" tabindex="6">Guardar  <i class="fa fa-save"></i></button>
                        </div>
                        <div class="col-md-2 form-group text-center">
                            <a href="/user" class="btn btn-secondary btn-block">Cancelar  <i class="fa fa-arrow-circle-left"></i></a>
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

    function solonumeros(event){
            key = event.keyCode || event.which;
            tecla = String.fromCharCode(key).toLowerCase();

            letras = "1234567890";

            especiales = [8,13,37,39,46,116];
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

    function validar(){
        var xseguir = true;
        var xcredit = document.getElementById("credit").value;
        if  (xcredit  === 'Y'){
            var xcredit_limit = document.getElementById("credit_limit").value;
            if  (xcredit_limit.length < 1 || xcredit_limit.value == 0.00){
                xseguir = false;
                document.getElementById("credit_limit_error").style.display = "block";
            } else {
                if (parseFloat(xcredit_limit, 10) <= 0){
                    xseguir = false;
                    document.getElementById("credit_limit_error").style.display = "block";
                }
            }
        }
        if (xseguir){
            document.view.submit();
        }
    }
</script>

@stop
