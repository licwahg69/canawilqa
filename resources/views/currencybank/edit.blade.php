@extends('adminlte::page')

@section('title', 'Editar Banco Destino')

@section('content_header')
    <h1 class="m-0 text-primary text-center"><b>Editar Banco Destino: </b><label class="text-dark">{{$currencybank->bankname}}</label></h1>
@stop

@section('content')
<form action="/currencybank" method="POST" id="view" name="view" class="formeli">
    @csrf
    <input type="hidden" id="currencybank_id" name="currencybank_id" value="{{$currencybank->id}}">
    <input type="hidden" id="toaction" name="toaction" value="update">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header"><i class="fas fa-landmark"></i>
                    <b> Datos del Banco</b>
                </div>
                <div class="card-body">
                    <div class='row'>
                        <div class="col-md-4 form-group">
                            <label for="country_id">País (*):</label>
                            <select id="country_id" name="country_id" class="form-control" tabindex="1" onchange="getBank(this.value),quitaMensaje()" autofocus>
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
                                        @if ($currencybank->country_id == $country->id)
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
                            <label for="bank_id">Nombre del Banco (*):</label>
                            <select id="bank_id" name="bank_id" class="form-control" tabindex="2" onclick="quitaMensaje()" onchange="getSerial(this.value)">
                                @if (old('bank_id') > 0)
                                    @foreach ($banks as $bank)
                                        @if (old('bank_id') == $bank->id)
                                            <option value="{{$bank->id}}" selected>{{$bank->bankname}}</option>
                                        @else
                                            <option value="{{$bank->id}}">{{$bank->bankname}}</option>
                                        @endif
                                    @endforeach
                                @else
                                    @foreach ($banks as $bank)
                                        @if ($currencybank->bank_id == $bank->id)
                                            <option value="{{$bank->id}}" selected>{{$bank->bankname}}</option>
                                        @else
                                            <option value="{{$bank->id}}">{{$bank->bankname}}</option>
                                        @endif
                                    @endforeach
                                @endif
                            </select>
                            <div id="bank_id_error" class="talert" style='display: none;'>
                                <p class="text-danger">El Banco es requerido</p>
                            </div>
                        </div>
                        <div class="col-md-3 form-group">
                            <label for="account_number">Número de cuenta (*):</label>
                            <input type="text" class="form-control" id="account_number" name="account_number" onkeypress="quitaMensaje()" maxlength="25" value="{{ old('account_number') ?? $currencybank->account_number ?? old('account_number') }}" placeholder="Número de cuenta" tabindex="3">
                            <div id="account_number_error" class="talert" style='display: none;'>
                                <p class="text-danger">El Número de cuenta es requerido</p>
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
                            <a href="/currencybank" class="btn btn-secondary btn-block" tabindex="5">Cancelar  <i class="fa fa-arrow-circle-left"></i></a>
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

    function getBank(xid){
        fetch(`/get_bank/${xid}`)
            .then(response => response.json())
            .then(jsondata => showBank(jsondata))
    }

    function showBank(jsondata){
        document.getElementById('account_number').value = '';

        let selectbank_id = document.getElementById('bank_id');
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

    function getSerial(xxid) {
        fetch(`/serial/${xxid}`)
            .then(response => response.json())
            .then(jsondata => showSerial(jsondata))
    }

    function showSerial(jsondata){
        let prefix = jsondata.prefix;

        document.getElementById("account_number").value = prefix;
        $('#account_number').focus();
    }

    function validar(){
        var xseguir = true;
        var xcountry_id = document.getElementById("country_id").value;
        if  (xcountry_id.length < 1){
            xseguir = false;
            document.getElementById("country_id_error").style.display = "block";
        }
        var xbank_id = document.getElementById("bank_id").value;
        if  (xbank_id.length < 1){
            xseguir = false;
            document.getElementById("bank_id_error").style.display = "block";
        }
        var xaccount_number = document.getElementById("account_number").value;
        if  (xaccount_number.length < 1){
            xseguir = false;
            document.getElementById("account_number_error").style.display = "block";
        }
        if (xseguir){
            document.view.submit();
        }
    }
</script>

@stop
