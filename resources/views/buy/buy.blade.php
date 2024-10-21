@extends('adminlte::page')

@section('title', 'Compras')

@section('content_header')
    <h1 class="m-0 text-primary text-center"><b>Compra de Divisas</b></h1>
@stop

@section('content')
<form action="/buy" method="POST" id="view" name="view">
    @csrf
    <input type="hidden" id="currency_id" name="currency_id" value="">
    <input type="hidden" id="currency2_id" name="currency2_id" value="{{$pcurrency_id}}">
    <input type="hidden" id="toaction" name="toaction" value="new">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header"><i class="fas fa-file-invoice-dollar"></i>
                    <b> Datos de la Compra</b>
                </div>
                <div class="card-body">
                    <div class='row'>
                        <div class="col-md-4 form-group">
                            <label for="country_id" class="form-label"><b>País destino de la compra (*):</b></label>
                            <select id="country_id" name="country_id" class="form-control" onchange="getBank(this.value)" onclick="quitaMensaje()" autofocus>
                                <option value="">Seleccionar</option>
                                @foreach ($countries as $country)
                                    <option value="{{$country->id}}">{{$country->countryname}}</option>
                                @endforeach
                            </select>
                            <div id="country_id_error" class="talert" style='display: none;'>
                                <p class="text-danger">El País destino es requerido</p>
                            </div>
                        </div>
                        <div class="col-md-5 form-group">
                            <label for="currencybank_id" class="form-label"><b>Banco destino de la compra (*):</b></label>
                            <select id="currencybank_id" name="currencybank_id" class="form-control" onchange="getAccount(this.value)" onclick="quitaMensaje()">
                                <option value="">Seleccionar</option>
                            </select>
                            <div id="currencybank_id_error" class="talert" style='display: none;'>
                                <p class="text-danger">El Banco destino es requerido</p>
                            </div>
                        </div>
                        <div class="col-md-3 form-group">
                            <label for="account_number" class="form-label"><b>Número de cuenta:</b></label>
                            <input disabled class="form-control height" type="text" id="account_number" name="account_number" value="" placeholder="Número de cuenta">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3 form-group">
                            <label for="purchased_amount" id="label_purchased_amount">Monto a comprar (*):</label>
                            <input type="text" class="form-control" id="purchased_amount" name="purchased_amount" onkeydown="quitaMensaje()" oninput="procesarValor(this)" onKeyPress="solonumeros(event)" value="{{ old('purchased_amount') }}" placeholder="Monto a comprar">
                            <input type="hidden" id="real_purchased_amount" name="real_purchased_amount" value="">
                            <div id="purchased_amount_error" class="talert" style='display: none;'>
                                <p class="text-danger">El Valor del Monto a comprar es requerido</p>
                            </div>
                        </div>
                        <div class="col-md-3 form-group">
                            <label for="exchange_rate" id="label_exchange_rate">Tasa de cambio en {{$pcurrency}}({{$psymbol}}) (*):</label>
                            <input type="text" class="form-control" style="color: red; background-color: white" id="exchange_rate" name="exchange_rate" oninput="procesarValor2(this)" onKeyPress="solonumeros(event)" value="" placeholder="Tasa de cambio">
                            <input type="hidden" id="real_exchange_rate" name="real_exchange_rate" value="">
                            <div id="exchange_rate_error" class="talert" style='display: none;'>
                                <p class="text-danger">La Tasa de cambio es requerida</p>
                            </div>
                        </div>
                        <div class="col-md-3 form-group">
                            <label for="converted_amount" id="label_converted_amount">Monto requerido en {{$pcurrency}}({{$psymbol}}):</label>
                            <input disabled type="text" class="form-control" style="color:darkgreen; background-color: white" id="converted_amount" name="converted_amount" value="0,00" placeholder="Monto requerido">
                            <input type="hidden" id="real_converted_amount" name="real_converted_amount" value="">
                        </div>
                    </div>
                    <div class="col-md-12 form-group">
                        <b>(*) Campos obligatorios</b>
                    </div>
                    <div class="row">
                        <div class="col-md-2 form-group text-center">
                            <button type="button" id="buttongrabar" onclick="validar()" class="btn btn-success btn-block">Guardar  <i class="fa fa-save"></i></button>
                        </div>
                        <div class="col-md-2 form-group text-center">
                            <a href="/home" class="btn btn-secondary btn-block">Cancelar  <i class="fa fa-arrow-circle-left"></i></a>
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

    function procesarValor(input) {
        let valorActual = input.value.replace(/\./g, '').replace(',', '.');

        // Convertimos el valor a un número flotante para realizar cálculos
        let numeroSinFormato = parseFloat(valorActual);

        document.getElementById('real_purchased_amount').value = numeroSinFormato;

        // Aplicamos la máscara al valor del input
        formatearNumero(input);
    }

    function formatearNumero(input) {
        // Eliminar cualquier carácter que no sea un número o una coma
        let valor = input.value.replace(/[^0-9,]/g, '');

        // Si hay más de una coma, eliminamos las adicionales
        if (valor.indexOf(',') !== -1) {
            let partes = valor.split(',');
            valor = partes[0] + ',' + partes[1].slice(0, 2);  // Limitar la parte decimal a dos dígitos
        }

        // Remover los puntos existentes para evitar conflictos
        valor = valor.replace(/\./g, '');

        // Añadir puntos como separadores de miles
        let valorConMiles = valor.replace(/\B(?=(\d{3})+(?!\d))/g, '.');

        // Actualizar el valor en el input
        input.value = valorConMiles;
    }

    function procesarValor2(input2) {
        let valorActual2 = input2.value.replace(/\./g, '').replace(',', '.');

        // Convertimos el valor a un número flotante para realizar cálculos
        let numeroSinFormato2 = parseFloat(valorActual2);

        document.getElementById('real_exchange_rate').value = numeroSinFormato2;

        // Realizamos el cálculo con el valor sin formato
        calcular(numeroSinFormato2);

        // Aplicamos la máscara al valor del input
        formatearNumero2(input2);
    }

    function calcular(xvalor){
        var xreal_purchased_amount = parseFloat(document.getElementById("real_purchased_amount").value);

        //var mountCommissionValue = (xvalor * xcommission).toFixed(2);
        var mountConverted_amount = (xvalor * xreal_purchased_amount);
        document.getElementById("converted_amount").value = formatearNumeroSimple(mountConverted_amount.toFixed(2));
        document.getElementById("real_converted_amount").value = mountConverted_amount;

    }

    function formatearNumero2(input2) {
        // Eliminar cualquier carácter que no sea un número o una coma
        let valor2 = input2.value.replace(/[^0-9,]/g, '');

        // Si hay más de una coma, eliminamos las adicionales
        if (valor2.indexOf(',') !== -1) {
            let partes2 = valor2.split(',');
            valor2 = partes2[0] + ',' + partes2[1].slice(0, 2);  // Limitar la parte decimal a dos dígitos
        }

        // Remover los puntos existentes para evitar conflictos
        valor2 = valor2.replace(/\./g, '');

        // Añadir puntos como separadores de miles
        let valorConMiles2 = valor2.replace(/\B(?=(\d{3})+(?!\d))/g, '.');

        // Actualizar el valor en el input
        input2.value = valorConMiles2;
    }

    function formatearNumeroSimple(valor) {
        // Convertir a string para poder manipular el valor
        let valorStr = valor.toString();

        // Separar la parte entera de la parte decimal
        let partes = valorStr.split('.');

        // Formatear la parte entera con separadores de miles
        let parteEnteraConMiles = partes[0].replace(/\B(?=(\d{3})+(?!\d))/g, '.');

        // Si hay parte decimal, la añadimos
        if (partes[1]) {
            return parteEnteraConMiles + ',' + partes[1].slice(0, 2); // Usamos coma como separador decimal
        } else {
            return parteEnteraConMiles; // Si no hay decimales, retornamos solo la parte entera formateada
        }
    }

    function getBank(xid){
        fetch(`/get_currencybank/${xid}`)
            .then(response => response.json())
            .then(jsondata => showBank(jsondata))
    }

    function showBank(jsondata){
        document.getElementById('account_number').value = '';
        let selectbank_id = document.getElementById('currencybank_id');
        selectbank_id.innerHTML = ''; // Limpiar contenido anterior

        // Crear y añadir una opción por defecto
        let defaultOption = document.createElement('option');
        defaultOption.value = '';
        defaultOption.text = 'Seleccionar';
        selectbank_id.appendChild(defaultOption);

        // Recorrer el array y crear opciones
        jsondata.forEach(bank => {
            let option = document.createElement('option');
            option.value = bank.id;  // Asumiendo que el id está en la propiedad 'id'
            option.text = bank.bankname;  // Asumiendo que el nombre del estado está en la propiedad 'name'
            selectbank_id.appendChild(option);
        });
    }

    function getAccount(xid){
        fetch(`/account2/${xid}`)
            .then(response => response.json())
            .then(jsondata => showAccount(jsondata))
    }

    function showAccount(jsondata){
        let account_number = jsondata.account_number;
        let currency = jsondata.currency;
        let symbol = jsondata.symbol;
        let currency_id = jsondata.currency_id;

        document.getElementById("account_number").value = account_number;
        document.getElementById("currency_id").value = currency_id;

        $('#label_purchased_amount').text('Monto a comprar en ' + currency + '(' + symbol + ')' + ' (*):');
    }

    function validar(){
        var xseguir = true;
        var xcountry_id = document.getElementById("country_id").value;
        if (xcountry_id.length < 1){
            xseguir = false;
            document.getElementById("country_id_error").style.display = "block";
        }
        var xcurrencybank_id = document.getElementById("currencybank_id").value;
        if (xcurrencybank_id.length < 1){
            xseguir = false;
            document.getElementById("currencybank_id_error").style.display = "block";
        }
        var xpurchased_amount = document.getElementById("purchased_amount").value;
        if (xpurchased_amount.length < 1){
            xseguir = false;
            document.getElementById("purchased_amount_error").style.display = "block";
        }
        var xexchange_rate = document.getElementById("exchange_rate").value;
        if (xexchange_rate.length < 1){
            xseguir = false;
            document.getElementById("exchange_rate_error").style.display = "block";
        }
        if (xseguir){
            document.view.submit();
        }
    }
</script>
@stop
