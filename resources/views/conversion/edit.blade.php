@extends('adminlte::page')

@section('title', 'Actualizar Conversión')

@section('content_header')
    <h1 class="m-0 text-primary text-center"><b>Actualizar Conversión</b></h1>
@stop

@section('content')
<form action="/conversion" method="POST" id="view" name="view">
    @csrf
    <input type="hidden" id="conversion_id" name="conversion_id" value="{{$conversion->id}}">
    <input type="hidden" id="twodecimals" name="twodecimals" value="">
    <input type="hidden" id="toaction" name="toaction" value="update">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header"><i class="fas fa-dollar-sign"></i>
                    <b> Datos de la Conversión</b>
                </div>
                <div class="card-body">
                    <div class='row'>
                        <div class="col-md-9 form-group">
                            <label for="conversion_description">Descripción de la conversión:</label>
                            <input disabled type="text" class="form-control" id="conversion_description" name="conversion_description" onkeydown="quitaMensaje()" value="{{ old('conversion_description') ?? $conversion->conversion_description ?? old('conversion_description') }}" placeholder="Valor de la conversión" tabindex="1">
                        </div>
                        <div class="col-md-3 form-group">
                            <label for="a_to_b">Descripción abreviada:</label>
                            <input disabled type="text" class="form-control" id="a_to_b" name="a_to_b" onkeydown="quitaMensaje()" value="{{ old('a_to_b') ?? trim($conversion->a_to_b) ?? old('a_to_b') }}" placeholder="Valor de la conversión">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-2 form-group">
                            <label for="typeuser" class="form-label"><b>Asignado a (*):</b> </label>
                            <select id="typeuser" name="typeuser" class="form-control" onchange="quitaMensaje()" tabindex="2" autofocus>
                                @if ($conversion->typeuser == 'ALI')
                                    <option value="ALI" selected>Aliado Comercial</option>
                                    <option value="USU">Usuario</option>
                                @else
                                    <option value="ALI">Aliado Comercial</option>
                                    <option value="USU" selected>Usuario</option>
                                @endif
                            </select>
                            <div id="typeuser_error" class="talert" style='display: none;'>
                                <p class="text-danger">La asignación al tipo de Usuario es requerida</p>
                            </div>
                        </div>
                        <div class="col-md-3 form-group">
                            <label for="conversion_value">Valor de la conversión (*):</label>
                            <input type="text" class="form-control" id="conversion_value" name="conversion_value" onkeydown="quitaMensaje()" onKeyPress="solonumeros(event)" value="{{ old('conversion_value') ?? $conversion->conversion_value ?? old('conversion_value') }}" placeholder="Valor de la conversión" tabindex="4">
                            <div id="conversion_value_error" class="talert" style='display: none;'>
                                <p class="text-danger">El Valor de la conversión es requerido</p>
                            </div>
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="reference_currency_id">Divisa de Referencia (*):</label>
                            <select id="reference_currency_id" name="reference_currency_id" class="form-control" tabindex="3" onchange="quitaMensaje()">
                                @foreach ($currencies as $currency)
                                    @if ($conversion->reference_currency_id == $currency->id)
                                        <option value="{{$currency->id}}" selected>{{$currency->full_description}}</option>
                                    @else
                                        <option value="{{$currency->id}}">{{$currency->full_description}}</option>
                                    @endif
                                @endforeach
                            </select>
                            <div id="reference_currency_id_error" class="talert" style='display: none;'>
                                <p class="text-danger">La Divisa de Referencia es requerida</p>
                            </div>
                        </div>
                        <div class="col-md-3 form-group">
                            <label for="reference_conversion_value">Valor de la referencia (*):</label>
                            <input type="text" class="form-control" id="reference_conversion_value" name="reference_conversion_value" onkeydown="quitaMensaje()" onKeyPress="solonumeros(event)" value="{{ old('reference_conversion_value') ?? $conversion->reference_conversion_value ?? old('reference_conversion_value') }}" placeholder="Valor de la referencia" tabindex="5">
                            <div id="reference_conversion_value_error" class="talert" style='display: none;'>
                                <p class="text-danger">El Valor de la referencia es requerido</p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-5 form-group">
                            <div class="form-check">
                                @if ($conversion->two_decimals == 'Y')
                                    <input class="form-check-input" type="checkbox" value="" id="two_decimals" name="two_decimals" checked>
                                @else
                                    <input class="form-check-input" type="checkbox" value="" id="two_decimals" name="two_decimals">
                                @endif
                                <label class="form-check-label" for="two_decimals">
                                    <b>Mostrar solo dos Decimáles</b>
                                </label>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label for="customer_commission">% Comisión del Aliado Comercial (*):</label>
                            <input type="text" class="form-control" id="customer_commission" name="customer_commission" onkeydown="quitaMensaje()" onKeyPress="solonumeros(event)" value="{{ old('customer_commission') ?? $conversion->customer_commission ?? old('customer_commission') }}" placeholder="% Comisión del Aliado Comercial">
                            <div id="customer_commission_error" class="talert" style='display: none;'>
                                <p class="text-danger">La Comisión del Aliado Comercial es requerida</p>
                            </div>
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="wholesaler_commission">% Comisión del Aliado Mayorista (*):</label>
                            <input type="text" class="form-control" id="wholesaler_commission" name="wholesaler_commission" onkeydown="quitaMensaje()" onKeyPress="solonumeros(event)" value="{{ old('wholesaler_commission') ?? $conversion->wholesaler_commission ?? old('wholesaler_commission') }}" placeholder="% Comisión del Aliado Mayorista">
                            <div id="wholesaler_commission_error" class="talert" style='display: none;'>
                                <p class="text-danger">La Comisión del Aliado Mayorista es requerida</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 form-group">
                        <b>(*) Campos obligatorios</b>
                    </div>
                    <div class="row">
                        <div class="col-md-2 form-group text-center">
                            <button type="button" onclick="validar()" class="btn btn-success btn-block" tabindex="6">Guardar  <i class="fa fa-save"></i></button>
                        </div>
                        <div class="col-md-2 form-group text-center">
                            <a href="/conversion" class="btn btn-secondary btn-block" tabindex="7">Cancelar  <i class="fa fa-arrow-circle-left"></i></a>
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
        var xtypeuser = document.getElementById("typeuser").value;
        if  (xtypeuser.length < 1){
            xseguir = false;
            document.getElementById("typeuser_error").style.display = "block";
        }
        var xconversion_value = document.getElementById("conversion_value").value;
        if  (xconversion_value.length < 1 || xconversion_value.value == 0.00){
            xseguir = false;
            document.getElementById("conversion_value_error").style.display = "block";
        } else {
            if (parseFloat(xconversion_value, 10) <= 0){
                xseguir = false;
                document.getElementById("conversion_value_error").style.display = "block";
            }
        }
        var xreference_currency_id = document.getElementById("reference_currency_id").value;
        if  (xreference_currency_id.length < 1){
            xseguir = false;
            document.getElementById("reference_currency_id_error").style.display = "block";
        }
        var xreference_conversion_value = document.getElementById("reference_conversion_value").value;
        if  (xreference_conversion_value.length < 1 || xreference_conversion_value.value == 0){
            xseguir = false;
            document.getElementById("reference_conversion_value_error").style.display = "block";
        } else {
            if (parseFloat(xreference_conversion_value, 10) <= 0){
                xseguir = false;
                document.getElementById("reference_conversion_value_error").style.display = "block";
            }
        }
        var xcustomer_commission = document.getElementById("customer_commission").value;
        if  (xcustomer_commission.length < 1 || xcustomer_commission.value == 0){
            xseguir = false;
            document.getElementById("customer_commission_error").style.display = "block";
        } else {
            if (parseFloat(xcustomer_commission, 10) <= 0){
                xseguir = false;
                document.getElementById("customer_commission_error").style.display = "block";
            }
        }
        var xwholesaler_commission = document.getElementById("wholesaler_commission").value;
        if  (xwholesaler_commission.length < 1 || xwholesaler_commission.value == 0){
            xseguir = false;
            document.getElementById("wholesaler_commission_error").style.display = "block";
        } else {
            if (parseFloat(xwholesaler_commission, 10) <= 0){
                xseguir = false;
                document.getElementById("wholesaler_commission_error").style.display = "block";
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
