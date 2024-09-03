@extends('adminlte::page')

@section('title', 'Crear Mensaje de Estatus')

@section('content_header')
    <h1 class="m-0 text-primary text-center"><b>Crear nuevo Mensaje de Estatus para la APP Canawil</b></h1>
@stop

@section('content')
<form action="/sys_status" method="POST" id="view" name="view">
    @csrf
    <input type="hidden" id="toaction" name="toaction" value="new">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header"><i class="fa fa-flag"></i>
                    <b> Datos del Estatus</b>
                </div>
                <div class="card-body">
                    <div class='row'>
                        <div class="col-md-9 form-group">
                            <label for="message">Mensaje de la APP (*):</label>
                            <input type="text" class="form-control" id="message" name="message" onkeypress="quitaMensaje()" maxlength="300" value="{{ old('message') }}" placeholder="Mensaje de la APP" autofocus>
                            <div id="message_error" class="talert" style='display: none;'>
                                <p class="text-danger">El Mensaje de la APP es requerido</p>
                            </div>
                        </div>
                        <div class="col-md-3 form-group">
                            <label for="stop">Detiene la APP (*):</label>
                            <select id="stop" name="stop" class="form-control" onchange="quitaMensaje()">
                                <option value="">Seleccionar</option>
                                <option value="SI">SI</option>
                                <option value="NO">NO</option>
                            </select>
                            <div id="stop_error" class="talert" style='display: none;'>
                                <p class="text-danger">Debe indicar si el mensaje detien la APP</p>
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
                            <a href="/sys_status" class="btn btn-secondary btn-block" tabindex="5">Cancelar  <i class="fa fa-arrow-circle-left"></i></a>
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
        var xmessage = document.getElementById("message").value;
        if  (xmessage.length < 1){
            xseguir = false;
            document.getElementById("message_error").style.display = "block";
        }
        var xstop = document.getElementById("stop").value;
        if  (xstop.length < 1){
            xseguir = false;
            document.getElementById("stop_error").style.display = "block";
        }
        if (xseguir){
            document.view.submit();
        }
    }
</script>

@stop
