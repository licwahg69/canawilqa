@extends('adminlte::page')

@section('title', 'Editar Banco de Canawil')

@section('content_header')
    <h1 class="m-0 text-primary text-center"><b>Editar Banco: </b><label class="text-dark">{{$banks->bank_name}}</label></h1>
@stop

@section('content')
<form action="/canawilbank" method="POST" id="view" name="view" class="formeli">
    @csrf
    <input type="hidden" id="canawilbank_id" name="canawilbank_id" value="{{$banks->id}}">
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
                            <select id="country_id" name="country_id" class="form-control" tabindex="1" onchange="getWayToPay(this.value), quitaMensaje()" autofocus>
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
                            <label for="bank_name">Banco (*):</label>
                            <input type="text" class="form-control" id="bank_name" name="bank_name" onkeypress="quitaMensaje()" maxlength="60" value="{{ old('bank_name') ?? $banks->bank_name ?? old('bank_name') }}" placeholder="Nombre del Banco" tabindex="2">
                            <div id="bank_name_error" class="talert" style='display: none;'>
                                <p class="text-danger">El Nombre del Banco es requerido</p>
                            </div>
                        </div>
                        <div class="col-md-3 form-group">
                            <label for="account_number">Número de Cuenta:</label>
                            <input type="text" class="form-control" id="account_number" name="account_number" onkeypress="quitaMensaje()" maxlength="25" value="{{ old('account_number') ?? $banks->account_number ?? old('account_number') }}" placeholder="Número de Cuenta" tabindex="3">
                            <div id="account_number_error" class="talert" style='display: none;'>
                                <p class="text-danger">El Número de Cuenta es requerido</p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label for="waytopay_id">Medio de pago (*):</label>
                            <select id="waytopay_id" name="waytopay_id" class="form-control" onchange="quitaMensaje()">
                                @foreach ($way_to_pays as $way_to_pay)
                                        @if ($banks->waytopay_id == $way_to_pay->id)
                                            <option value="{{$way_to_pay->id}}" selected>{{$way_to_pay->description}}</option>
                                        @else
                                            <option value="{{$way_to_pay->id}}">{{$way_to_pay->description}}</option>
                                        @endif
                                    @endforeach
                            </select>
                            <div id="waytopay_id_error" class="talert" style='display: none;'>
                                <p class="text-danger">El Medio de pago es requerido</p>
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
                            <a href="/canawilbank" class="btn btn-secondary btn-block" tabindex="5">Cancelar  <i class="fa fa-arrow-circle-left"></i></a>
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

    function getWayToPay(xxid) {
        fetch(`/getway/${xxid}`)
            .then(response => response.json())
            .then(jsondata => showDescripcion(jsondata))
    }

    function showDescripcion(jsondata){

        let selectWaytoPay = document.getElementById('waytopay_id');
        selectWaytoPay.innerHTML = ''; // Limpiar contenido anterior

        // Crear y añadir una opción por defecto
        let defaultOption2 = document.createElement('option');
        defaultOption2.value = '';
        defaultOption2.text = 'Seleccionar';
        selectWaytoPay.appendChild(defaultOption2);

        jsondata.forEach(way => {
            let option2 = document.createElement('option');
            option2.value = way.id;  // Asumiendo que el id está en la propiedad 'id'
            option2.text = way.description;  // Asumiendo que el nombre del estado está en la propiedad 'name'
            selectWaytoPay.appendChild(option2);
        });
    }

    function validar(){
        var xseguir = true;
        var xbankname = document.getElementById("bank_name").value;
        if  (xbankname.length < 1){
            xseguir = false;
            document.getElementById("bank_name_error").style.display = "block";
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
