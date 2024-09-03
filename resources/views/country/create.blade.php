@extends('adminlte::page')

@section('title', 'Crear País')

@section('content_header')
    <h1 class="m-0 text-primary text-center"><b>Crear nuevo País</b></h1>
@stop

@section('content')
<form action="/country" method="POST" id="view" name="view">
    @csrf
    <input type="hidden" id="toaction" name="toaction" value="new">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header"><i class="fas fa-globe"></i>
                    <b> Datos del País</b>
                </div>
                <div class="card-body">
                    <div class='row'>
                        <div class="col-md-10 form-group">
                            <label for="countryname">Nombre del País (*):</label>
                            <input type="text" class="form-control" id="countryname" name="countryname" onkeypress="quitaMensaje()" maxlength="80" value="{{ old('countryname') }}" placeholder="Nombre del País" tabindex="1" autofocus>
                            <div id="countryname_error" class="talert" style='display: none;'>
                                <p class="text-danger">El Nombre del País es requerido</p>
                            </div>
                        </div>
                        <div class="col-md-2 form-group">
                            <label for="phone_code">Código WhatsApp (*):</label>
                            <input type="text" class="form-control" id="phone_code" name="phone_code" onkeypress="quitaMensaje()" maxlength="8" value="{{ old('phone_code') }}" placeholder="Código WhatsApp" tabindex="2">
                            <div id="phone_code_error" class="talert" style='display: none;'>
                                <p class="text-danger">El Código WhatsApp es requerido</p>
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
                            <a href="/country" class="btn btn-secondary btn-block" tabindex="5">Cancelar  <i class="fa fa-arrow-circle-left"></i></a>
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
    <label class="text-primary">© {{ date_format(date_create(date("Y")),"Y") }} CANAWIL Cambios</label>, todos los derechos reservados.
</div>
@stop

@section('js')
<script>
    function quitaMensaje(){
        $(".talert").css("display", "none");
    }

    function validar(){
        var xseguir = true;
        var xcountryname = document.getElementById("countryname").value;
        if  (xcountryname.length < 1){
            xseguir = false;
            document.getElementById("countryname_error").style.display = "block";
        }
        var xphone_code = document.getElementById("phone_code").value;
        if  (xphone_code.length < 1){
            xseguir = false;
            document.getElementById("phone_code_error").style.display = "block";
        }
        if (xseguir){
            document.view.submit();
        }
    }
</script>

@stop
