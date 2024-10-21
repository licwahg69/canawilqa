@extends('adminlte::page')

@section('title', 'Editar Banco')

@section('content_header')
    <h1 class="m-0 text-primary text-center"><b>Editar Banco: </b><label class="text-dark">{{$banks->bankname}}</label></h1>
@stop

@section('content')
<form action="/bank" method="POST" id="view" name="view" class="formeli">
    @csrf
    <input type="hidden" id="bank_id" name="bank_id" value="{{$banks->id}}">
    <input type="hidden" id="toaction" name="toaction" value="update">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header"><i class="fas fa-landmark"></i>
                    <b> Datos del Banco</b>
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
                                    @foreach ($countries as $country)
                                        @if ($banks->country_id == $country->id)
                                            <option value="{{$country->id}}" selected>{{$country->countryname}}</option>
                                        @else
                                            <option value="{{$country->id}}">{{$country->countryname}}</option>
                                        @endif
                                    @endforeach
                                @endif
                            </select>
                            <div id="country_id_error" class="talert" style='display: none;'>
                                <p class="text-danger">El País del Banco es requerido</p>
                            </div>
                        </div>
                        <div class="col-md-5 form-group">
                            <label for="bankname">Banco (*):</label>
                            <input type="text" class="form-control" id="bankname" name="bankname" onkeypress="quitaMensaje()" maxlength="50" value="{{ old('bankname') ?? $banks->bankname ?? old('bankname') }}" placeholder="Nombre del Banco" tabindex="2">
                            <div id="bankname_error" class="talert" style='display: none;'>
                                <p class="text-danger">El Nombre del Banco es requerido</p>
                            </div>
                        </div>
                        <div class="col-md-2 form-group">
                            <label for="prefix">Prefijo del Banco:</label>
                            <input type="text" class="form-control" id="prefix" name="prefix" onkeypress="quitaMensaje()" maxlength="4" value="{{ old('prefix') ?? $banks->prefix ?? old('prefix') }}" placeholder="Prefijo del Banco" tabindex="3">
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
                            <a href="/bank" class="btn btn-secondary btn-block" tabindex="5">Cancelar  <i class="fa fa-arrow-circle-left"></i></a>
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
        var xbankname = document.getElementById("bankname").value;
        if  (xbankname.length < 1){
            xseguir = false;
            document.getElementById("bankname_error").style.display = "block";
        }
        if (xseguir){
            document.view.submit();
        }
    }
</script>

@stop
