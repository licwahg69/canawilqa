@extends('adminlte::page')

@section('title', 'Crear Tipo de Documento')

@section('content_header')
    <h1 class="m-0 text-primary text-center"><b>Crear nuevo Tipo de Documento</b></h1>
@stop

@section('content')
<form action="/typedoc" method="POST" id="view" name="view">
    @csrf
    <input type="hidden" id="toaction" name="toaction" value="new">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header"><i class="fas fa-list"></i>
                    <b> Datos del Tipo de Documento</b>
                </div>
                <div class="card-body">
                    <div class='row'>
                        <div class="col-md-5 form-group">
                            <label for="country_id">País (*):</label>
                            <select id="country_id" name="country_id" class="form-control" tabindex="1" onchange="quitaMensaje()" autofocus>
                                @if (old('country_id') > 0)
                                    @foreach ($countries as $country)
                                        @if (old('country_id') == $country->id)
                                            <option value="{{$country->id}}" selected>{{$country->countryname}}</option>
                                        @else
                                            <option value="{{$country->id}}">{{$country->countryname}}</option>
                                        @endif
                                    @endforeach
                                @else
                                    <option value="">Seleccionar</option>
                                    @foreach ($countries as $country)
                                        <option value="{{$country->id}}">{{$country->countryname}}</option>
                                    @endforeach
                                @endif
                            </select>
                            <div id="country_id_error" class="talert" style='display: none;'>
                                <p class="text-danger">El País del Tipo de Documento es requerido</p>
                            </div>
                        </div>
                        <div class="col-md-7 form-group">
                            <label for="description">Descripción (*):</label>
                            <input type="text" class="form-control" id="description" name="description" onkeypress="quitaMensaje()" maxlength="40" value="{{ old('bankname') }}" placeholder="Descripción" tabindex="2">
                            <div id="description_error" class="talert" style='display: none;'>
                                <p class="text-danger">La descripción del Tipo de Documento es requerida</p>
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
                            <a href="/typedoc" class="btn btn-secondary btn-block" tabindex="5">Cancelar  <i class="fa fa-arrow-circle-left"></i></a>
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

    function validar(){
        var xseguir = true;
        var xcountry_id = document.getElementById("country_id").value;
        if  (xcountry_id.length < 1){
            xseguir = false;
            document.getElementById("country_id_error").style.display = "block";
        }
        var xdescription = document.getElementById("description").value;
        if  (xdescription.length < 1){
            xseguir = false;
            document.getElementById("description_error").style.display = "block";
        }
        if (xseguir){
            document.view.submit();
        }
    }
</script>

@stop
