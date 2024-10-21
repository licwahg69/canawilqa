@extends('adminlte::page')

@section('title', 'Crear Conversión')

@section('content_header')
    <h1 class="m-0 text-primary text-center"><b>Crear nueva Conversión</b></h1>
@stop

@section('content')
<form action="/conversion" method="POST" id="view" name="view">
    @csrf
    <input type="hidden" id="twodecimals" name="twodecimals" value="">
    <input type="hidden" id="toaction" name="toaction" value="new">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header"><i class="fas fa-dollar-sign"></i>
                    <b> Datos de la Conversión</b>
                </div>
                <div class="card-body">
                    <div class='row'>
                        <div class="col-md-4 form-group">
                            <label for="currency_id">Divisa a convertir (*):</label>
                            <select id="currency_id" name="currency_id" class="form-control" tabindex="1" onchange="quitaMensaje()" autofocus>
                                @if (old('currency_id') > 0)
                                    @foreach ($currencies as $currency)
                                        @if (old('currency_id') == $currency->id)
                                            <option value="{{$currency->id}}" selected>{{$currency->full_description}}</option>
                                        @else
                                            <option value="{{$currency->id}}">{{$currency->full_description}}</option>
                                        @endif
                                    @endforeach
                                @else
                                    <option value="">Seleccionar</option>
                                    @foreach ($currencies as $currency)
                                        <option value="{{$currency->id}}">{{$currency->full_description}}</option>
                                    @endforeach
                                @endif
                            </select>
                            <div id="currency_id_error" class="talert" style='display: none;'>
                                <p class="text-danger">La Divisa a convertir es requerida</p>
                            </div>
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="currency2_id">Divisa del Cambio (*):</label>
                            <select id="currency2_id" name="currency2_id" class="form-control" tabindex="2" onchange="quitaMensaje()">
                                @if (old('currency2_id') > 0)
                                    @foreach ($currencies as $currency)
                                        @if (old('currency2_id') == $currency->id)
                                            <option value="{{$currency->id}}" selected>{{$currency->full_description}}</option>
                                        @else
                                            <option value="{{$currency->id}}">{{$currency->full_description}}</option>
                                        @endif
                                    @endforeach
                                @else
                                    <option value="">Seleccionar</option>
                                    @foreach ($currencies as $currency)
                                        <option value="{{$currency->id}}">{{$currency->full_description}}</option>
                                    @endforeach
                                @endif
                            </select>
                            <div id="currency2_id_error" class="talert" style='display: none;'>
                                <p class="text-danger">La Divisa del cambio es requerida</p>
                            </div>
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="conversion_value">Valor de la conversión (*):</label>
                            <input type="text" class="form-control" id="conversion_value" name="conversion_value" onkeydown="quitaMensaje()" onKeyPress="solonumeros(event)" value="{{ old('conversion_value') }}" placeholder="Valor de la conversión" tabindex="3">
                            <div id="conversion_value_error" class="talert" style='display: none;'>
                                <p class="text-danger">El Valor de la conversión es requerido</p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label for="typeuser" class="form-label"><b>Asignado a tipo de usuario (*):</b></label>
                            <select id="typeuser" name="typeuser" class="form-control" onchange="quitaMensaje()" tabindex="4">
                                <option value="">Seleccionar</option>
                                <option value="ALI">Aliado Comercial</option>
                                <option value="USU">Usuario</option>
                            </select>
                            <div id="typeuser_error" class="talert" style='display: none;'>
                                <p class="text-danger">La asignación al tipo de Usuario es requerida</p>
                            </div>
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="reference_currency_id">Divisa de Referencia (*):</label>
                            <select id="reference_currency_id" name="reference_currency_id" class="form-control" tabindex="5" onchange="quitaMensaje()">
                                @if (old('reference_currency_id') > 0)
                                    @foreach ($currencies as $currency)
                                        @if (old('reference_currency_id') == $currency->id)
                                            <option value="{{$currency->id}}" selected>{{$currency->full_description}}</option>
                                        @else
                                            <option value="{{$currency->id}}">{{$currency->full_description}}</option>
                                        @endif
                                    @endforeach
                                @else
                                    <option value="">Seleccionar</option>
                                    @foreach ($currencies as $currency)
                                        <option value="{{$currency->id}}">{{$currency->full_description}}</option>
                                    @endforeach
                                @endif
                            </select>
                            <div id="reference_currency_id_error" class="talert" style='display: none;'>
                                <p class="text-danger">La Divisa de Referencia es requerida</p>
                            </div>
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="reference_conversion_value">Valor de la referencia (*):</label>
                            <input type="text" class="form-control" id="reference_conversion_value" name="reference_conversion_value" onkeydown="quitaMensaje()" onKeyPress="solonumeros(event)" value="{{ old('reference_conversion_value') }}" placeholder="Valor de la referencia" tabindex="6">
                            <div id="reference_conversion_value_error" class="talert" style='display: none;'>
                                <p class="text-danger">El Valor de la referencia es requerido</p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-5 form-group">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="two_decimals" name="two_decimals">
                                <label class="form-check-label" for="two_decimals">
                                    <b>Mostrar solo dos Decimáles</b>
                                </label>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-3 form-group">
                            <label for="customer_commission">% Comisión del Aliado (*):</label>
                            <input type="text" class="form-control" id="customer_commission" name="customer_commission" onkeydown="quitaMensaje()" onKeyPress="solonumeros(event)" value="{{ old('customer_commission') }}" placeholder="% Comisión del Aliado">
                            <div id="customer_commission_error" class="talert" style='display: none;'>
                                <p class="text-danger">La Comisión del Aliado es requerida</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 form-group">
                        <b>(*) Campos obligatorios</b>
                    </div>
                    <div class="row">
                        <div class="col-md-2 form-group text-center">
                            <button type="button" onclick="validar()" class="btn btn-success btn-block" tabindex="7">Guardar  <i class="fa fa-save"></i></button>
                        </div>
                        <div class="col-md-2 form-group text-center">
                            <a href="/conversion" class="btn btn-secondary btn-block" tabindex="8">Cancelar  <i class="fa fa-arrow-circle-left"></i></a>
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
        var xcurrency_id = document.getElementById("currency_id").value;
        if  (xcurrency_id.length < 1){
            xseguir = false;
            document.getElementById("currency_id_error").style.display = "block";
        }
        var xcurrency2_id = document.getElementById("currency2_id").value;
        if  (xcurrency2_id.length < 1){
            xseguir = false;
            document.getElementById("currency2_id_error").style.display = "block";
        }
        var xconversion_value = document.getElementById("conversion_value").value;
        if  (xconversion_value.length < 1){
            xseguir = false;
            document.getElementById("conversion_value_error").style.display = "block";
        } else {
            if (parseFloat(xconversion_value, 10) <= 0){
                xseguir = false;
                document.getElementById("conversion_value_error").style.display = "block";
            }
        }
        var xtypeuser = document.getElementById("typeuser").value;
        if  (xtypeuser.length < 1){
            xseguir = false;
            document.getElementById("typeuser_error").style.display = "block";
        }
        var xreference_currency_id = document.getElementById("reference_currency_id").value;
        if  (xreference_currency_id.length < 1){
            xseguir = false;
            document.getElementById("reference_currency_id_error").style.display = "block";
        }
        var xreference_conversion_value = document.getElementById("reference_conversion_value").value;
        if  (xreference_conversion_value.length < 1){
            xseguir = false;
            document.getElementById("reference_conversion_value_error").style.display = "block";
        } else {
            if (parseFloat(xreference_conversion_value, 10) <= 0){
                xseguir = false;
                document.getElementById("reference_conversion_value_error").style.display = "block";
            }
        }
        var xcustomer_commission = document.getElementById("customer_commission").value;
        if  (xcustomer_commission.length < 1 || xreference_conversion_value.value == 0){
            xseguir = false;
            document.getElementById("customer_commission_error").style.display = "block";
        } else {
            if (parseFloat(xcustomer_commission, 10) <= 0){
                xseguir = false;
                document.getElementById("customer_commission_error").style.display = "block";
            }
        }
        let checkbox = document.getElementById('two_decimals');
        // Verificar si el checkbox está marcado
        if (checkbox.checked) {
            document.getElementById("twodecimals").value = 'Y';
        } else {
            document.getElementById("twodecimals").value = 'N';
        }
        if (xseguir){
            document.view.submit();
        }
    }
</script>

@stop
