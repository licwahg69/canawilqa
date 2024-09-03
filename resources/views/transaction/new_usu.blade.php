@extends('adminlte::page')

@section('title', 'Transacciones')

@section('content_header')
    <h1 class="m-0 text-primary text-center"><b>Transacciones</b></h1>
@stop

@section('content')
<style>
    .mm-dropdowns {
        padding: 10px;
    }

    .mm-dropdown {
        margin-top: 10px;
    }
    .mm-dropdown-toggle {
        background: #2d2e83;
        border: none;
        color: white;
        padding: 10px;
        cursor: pointer;
        width: 100%;
        text-align: left;
        border-radius: 4px;
        font-size: 16px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .mm-dropdown-menu {
        display: none;
        background: #fff;
        border: 1px solid #ddd;
        border-radius: 4px;
        margin-top: 5px;
    }

    .mm-dropdown-menu a {
        display: block;
        padding: 10px;
        color: #333;
        text-decoration: none;
    }

    .mm-dropdown-menu a:hover {
        background: #f7f7f7;
    }

    .mm-dropdown-menu a.selected {
        background: rgb(149, 193, 31);
        color: white;
    }

    .mm-dropdown-filter {
        width: 100%;
        box-sizing: border-box;
        padding: 10px;
        margin-bottom: 10px;
        border: 1px solid #ddd;
        border-radius: 4px;
    }
</style>
<form action="/transaction" method="POST" id="view" name="view">
    @csrf
    <input type="hidden" id="symbol2" name="symbol2" value="">
    <input type="hidden" id="symbol3" name="symbol3" value="">
    <input type="hidden" id="phonecode" name="phonecode" value="">
    <input type="hidden" id="document_id" name="document_id" value="">
    <input type="hidden" id="payer_id" name="payer_id" value="{{$payer_id}}">
    <input type="hidden" id="country2_id" name="country2_id" value="">
    <input type="hidden" id="favorite_value" name="favorite_value" value="">
    <input type="hidden" id="type_screen" name="type_screen" value="">
    <input type="hidden" id="toaction" name="toaction" value="new_usu">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header"><i class="fas fa-cash-register"></i>
                    <b> Datos de la Transacción</b>
                </div>
                <div class="card-body">
                    <div class='row'>
                        <div class="col-md-3 form-group">
                            <label for="conversion_id" class="form-label"><b>Tipo de cambio (*):</b></label>
                            <select id="conversion_id" name="conversion_id" class="form-control" onchange="getDescripcion(this.value)" onclick="quitaMensaje()" autofocus>
                                @if (old('conversion_id') > 0)
                                    @foreach ($conversions as $conversion)
                                        @if (old('conversion_id') == $conversion->id)
                                            <option value="{{$conversion->id}}" selected>{{$conversion->a_to_b}}</option>
                                        @else
                                            <option value="{{$conversion->id}}">{{$conversion->a_to_b}}</option>
                                        @endif
                                    @endforeach
                                @else
                                    <option value="">Seleccionar</option>
                                    @foreach ($conversions as $conversion)
                                        <option value="{{$conversion->id}}">{{$conversion->a_to_b}}</option>
                                    @endforeach
                                @endif
                            </select>
                            <div id="conversion_id_error" class="talert" style='display: none;'>
                                <p class="text-danger">El tipo de cambio es requerido</p>
                            </div>
                        </div>
                        <div class="col-md-9 form-group">
                            <label for="conversion_description" class="form-label"><b>Descripción:</b></label>
                            <input disabled class="form-control height" type="text" id="conversion_description" name="conversion_description" value="">
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-3 form-group">
                            <label for="mount_value" id="label_mount_value">Monto a cambiar (*):</label>
                            <input type="text" class="form-control" id="mount_value" name="mount_value" onkeydown="quitaMensaje()" oninput="procesarValor(this)" onKeyPress="solonumeros(event)" value="{{ old('mount_value') }}" placeholder="Monto a cambiar">
                            <input type="hidden" id="real_mount_value" name="real_mount_value" value="">
                            <div id="mount_value_error" class="talert" style='display: none;'>
                                <p class="text-danger">El Valor de la conversión es requerido</p>
                            </div>
                        </div>
                        <div class="col-md-2 form-group">
                            <label for="conversion_value" id="label_conversion_value">Tasa de cambio:</label>
                            <input disabled type="text" class="form-control" style="color: red; background-color: white" id="conversion_value" name="conversion_value" value="" placeholder="Tasa de cambio">
                            <input type="hidden" id="real_conversion_value" name="real_conversion_value" value="">
                        </div>
                        <div class="col-md-3 form-group">
                            <label for="mount_change" id="label_mount_change">Monto a pagar:</label>
                            <input disabled type="text" class="form-control" style="color:darkgreen; background-color: white" id="mount_change" name="mount_change" value="0.00" placeholder="Monto a pagar">
                            <input type="hidden" id="mount_change2" name="mount_change2" value="">
                        </div>
                        <div class="col-md-2 form-group">
                            <label for="reference_conversion_value" id="label_reference_conversion_value">Tasa Referencial:</label>
                            <input disabled type="text" class="form-control" style="color: red; background-color: white" id="reference_conversion_value" name="reference_conversion_value" value="" placeholder="Tasa Referencial">
                            <input type="hidden" id="real_reference_conversion_value" name="real_reference_conversion_value" value="">
                        </div>
                        <div class="col-md-2 form-group">
                            <label for="mount_reference" id="label_mount_reference">Monto Referencial:</label>
                            <input disabled type="text" class="form-control" style="color: darkgreen; background-color: white" id="mount_reference" name="mount_reference" value="0.00" placeholder="Monto Referencial">
                            <input type="hidden" id="mount_reference2" name="mount_reference2" value="">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="card">
                <div class="card-header"><i class="fas fa-list"></i>
                    <b> Cuentas</b>
                </div>
                <div class="card-body">
                    <div class="row">
                        <label for="search">Buscar por: </label>
                    </div>
                    <div class="row">
                        <div class="col-md-5 form-group">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio1" value="option1" checked>
                                <label class="form-check-label" for="inlineRadio1">N° Cuenta</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio2" value="option2">
                                <label class="form-check-label" for="inlineRadio2">N° Documento ID</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio3" value="option3">
                                <label class="form-check-label" for="inlineRadio3">Nombre Titular</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <div class="input-group mb-3">
                                <input class="form-control" type="text" id="search_document" name="search_document" placeholder="Introduzca el dato a buscar" value="" onkeydown="quitaMensaje()">
                                <button class="btn btn-primary btn-sm height" type="button" onclick="searchDoc()">
                                    Buscar
                                </button>
                            </div>
                            <div id="search_document_error1" class="talert" style='display: none;'>
                                <p class="text-danger">El Número de cuenta no existe</p>
                            </div>
                            <div id="search_document_error2" class="talert" style='display: none;'>
                                <p class="text-danger">El Número de documento no existe</p>
                            </div>
                            <div id="search_document_error3" class="talert" style='display: none;'>
                                <p class="text-danger">No puede hacer busquedas en blanco</p>
                            </div>
                        </div>
                        <div class="col-md-2 form-group">
                            <button type="button" onclick="crearDoc()" data-bs-toggle="modal" data-bs-target="#myModal" class="btn btn-success btn-block">Crear Cuenta  <i class="far fa-folder"></i></button>
                        </div>
                        <div class="col-md-2 form-group">
                            <button type="button" data-bs-toggle="modal" data-bs-target="#myModal2" class="btn btn-primary btn-block">Favoritos  <i class="fas fa-wifi"></i></button>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label for="doc_description" class="form-label"><b>Tipo de Documento:</b></label>
                            <input disabled class="form-control height" type="text" id="doc_description" name="doc_description" value="" placeholder="Tipo de Documento">
                        </div>
                        <div class="col-md-3 form-group">
                            <label for="numdoc" class="form-label"><b>Número Documento ID:</b></label>
                            <input disabled class="form-control height" type="text" id="numdoc" name="numdoc" value="" placeholder="Número Documento ID">
                        </div>
                        <div class="col-md-5 form-group">
                            <label for="account_holder" class="form-label"><b>Nombre del Titular:</b></label>
                            <input disabled class="form-control height" type="text" id="account_holder" name="account_holder" value="" placeholder="Nombre del Titular">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-8 form-group">
                            <label for="bankname" class="form-label"><b>Banco:</b></label>
                            <input disabled class="form-control height" type="text" id="bankname" name="bankname" value="" placeholder="Banco">
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="account_number" class="form-label"><b>Número de cuenta:</b></label>
                            <input disabled class="form-control height" type="text" id="account_number" name="account_number" value="" placeholder="Número de cuenta">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="favorite" name="favorite">
                                <label class="form-check-label" style="color:blue" for="favorite">
                                    <b>Marcar la cuenta como favorita</b>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-8 form-group">
                            <label for="payer_name" class="form-label"><b>Nombre del pagador:</b></label>
                            <input disabled type="text" class="form-control" id="payer_name" name="payer_name" value="{{$payer_name}}">
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="cellphone" class="form-label"><b>Celular:</b></label>
                            <div class="input-group mb-3">
                                <span class="input-group-text height"><i class="bi bi-whatsapp"></i></span>
                                <span class="input-group-text height" id="spancodigo">{{ $phone_code }}</span>
                                <input disabled class="form-control" type="text" id="cellphone" name="cellphone" maxlength=15 value="{{$onlycellphone}}">
                            </div>
                        </div>
                        <input type="hidden" id="selectedFavorite" name="selectedFavorite" value="">
                    </div>
                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label for="canawilbank_id" class="form-label"><b>Banco de Canawil (*):</b></label>
                            <select id="canawilbank_id" name="canawilbank_id" class="form-control" onchange="getAccount(this.value)" onclick="quitaMensaje()">
                                <option value="">Seleccionar</option>
                            </select>
                            <div id="canawilbank_id_error" class="talert" style='display: none;'>
                                <p class="text-danger">El Banco de Canawil es requerido</p>
                            </div>
                        </div>
                        <div class="col-md-3 form-group">
                            <label for="canawil_account_number" class="form-label"><b>Cuenta:</b></label>
                            <input disabled class="form-control" type="text" id="canawil_account_number" name="canawil_account_number" value="" placeholder="Cuenta">
                        </div>
                        <div class="col-md-3 form-group">
                            <label for="waytopay_id" class="form-label"><b>Medio para el envío:</b></label>
                            <select id="waytopay_id" name="waytopay_id" class="form-control" onchange="getWay(this.value)" onclick="quitaMensaje()">
                                <option value="">Seleccionar</option>
                            </select>
                            <div id="waytopay_id_error" class="talert" style='display: none;'>
                                <p class="text-danger">El Medio para el envío es requerido</p>
                            </div>
                        </div>
                        <div class="col-md-2 form-group">
                            <label for="waytopay_reference" class="form-label" id="label_reference"><b>Referencia:</b></label>
                            <input class="form-control" type="text" id="waytopay_reference" name="waytopay_reference" value="" placeholder="Referencia">
                        </div>
                    </div>
                    <br>
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
        <div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Crear Cuenta</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label for="typedoc_id" class="form-label"><b>Tipo de documento (*):</b></label>
                                    <select id="typedoc_id" name="typedoc_id" class="form-control" onclick="quitaMensaje()">
                                        <option value="">Seleccionar</option>
                                    </select>
                                    <div id="typedoc_id_error" class="talert" style='display: none;'>
                                        <p class="text-danger">El tipo de documento es requerido</p>
                                    </div>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for="create_numdoc" class="form-label"><b>Número Documento ID (*):</b></label>
                                    <input class="form-control" type="text" id="create_numdoc" name="create_numdoc" value="" placeholder="Número Documento ID" maxlength="20" onkeydown="quitaMensaje()">
                                    <div id="create_numdoc_error" class="talert" style='display: none;'>
                                        <p class="text-danger">El Número Documento ID es requerido</p>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 form-group">
                                    <label for="create_account_holder" class="form-label"><b>Nombre del Titular (*):</b></label>
                                    <input class="form-control" type="text" id="create_account_holder" name="create_account_holder" value="" placeholder="Nombre del Titular" maxlength="100" onkeydown="quitaMensaje()">
                                    <div id="create_account_holder_error" class="talert" style='display: none;'>
                                        <p class="text-danger">El Nombre del Titular es requerido</p>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label for="bank_id" class="form-label"><b>Banco (*):</b></label>
                                    <select id="bank_id" name="bank_id" class="form-control" onclick="quitaMensaje()" onchange="getSerial(this.value)">
                                        <option value="">Seleccionar</option>
                                    </select>
                                    <div id="bank_id_error" class="talert" style='display: none;'>
                                        <p class="text-danger">El Banco es requerido</p>
                                    </div>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for="create_account_number" class="form-label"><b>Número de cuenta (*):</b></label>
                                    <input class="form-control" type="text" id="create_account_number" name="create_account_number" value="" placeholder="Número de cuenta" maxlength="20" onkeydown="quitaMensaje()">
                                    <div id="create_account_number_error" class="talert" style='display: none;'>
                                        <p class="text-danger">El Número de cuenta es requerido</p>
                                    </div>
                                    <div id="create_account_number_exist" class="talert" style='display: none;'>
                                        <p class="text-danger">Ya existe este Número de cuenta</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-primary" onclick="grabarDoc()">Grabar</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="myModal2" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Lista de Favoritos</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-md-12 form-group">
                                    <div class="mm-dropdowns">
                                        <div class="mm-dropdown">
                                            <button class="mm-dropdown-toggle"><b>Favoritos</b></button>
                                            <div class="mm-dropdown-menu" id="favoriteButton">
                                                <input type="text" id="favoriteFilter" class="mm-dropdown-filter" placeholder="Filtrar">
                                                @foreach ($documents as $document)
                                                    <a href="#" onclick="selectFavorite({{ $document->id }})" class="mm-dropdown-option">{{ $document->complete_description }}</a>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal" onclick="seleFav()">Seleccionar</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="myModal3" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Resultados de la Busqueda</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-md-12 form-group">
                                    <div class="mm-dropdowns">
                                        <div class="mm-dropdown">
                                            <button class="mm-dropdown-toggle"><b>Coincidencias encontradas por Nombre</b></button>
                                            <div class="mm-dropdown-menu" id="nombreButton">
                                                <input type="text" id="nombreFilter" class="mm-dropdown-filter" placeholder="Filtrar">
                                                <a href="#" class="mm-dropdown-option">Seleccionar</a>
                                            </div>
                                            <input type="hidden" id="selectedNombre" name="selectedNombre" value="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal" onclick="seleNom()">Seleccionar</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="myModal4" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Resultados de la Busqueda</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-md-12 form-group">
                                    <div class="mm-dropdowns">
                                        <div class="mm-dropdown">
                                            <button class="mm-dropdown-toggle"><b>Coincidencias encontradas por Número de Documento</b></button>
                                            <div class="mm-dropdown-menu" id="numdocButton">
                                                <a href="#" class="mm-dropdown-option">Seleccionar</a>
                                            </div>
                                            <input type="hidden" id="selectedNumdoc" name="selectedNumdoc" value="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal" onclick="seleNumdoc()">Seleccionar</button>
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
    document.addEventListener('DOMContentLoaded', function() {
        if (window.innerWidth > 768) {
            document.getElementById("type_screen").value = "W";
        } else {
            document.getElementById("type_screen").value = "M";
        }
    });
</script>
<script>
    function quitaMensaje(){
        $(".talert").css("display", "none");
    }

    document.querySelectorAll('.mm-dropdown-toggle').forEach(button => {
        button.addEventListener('click', function(event) {
            event.preventDefault();
            const dropdownMenu = this.nextElementSibling;
            if (dropdownMenu.style.display === 'none' || dropdownMenu.style.display === '') {
                dropdownMenu.style.display = 'block';
            } else {
                dropdownMenu.style.display = 'none';
            }
        });
    });

    document.querySelectorAll('.mm-dropdown-option').forEach(option => {
        option.addEventListener('click', function(event) {
            event.preventDefault();
            const dropdownMenu = this.parentElement;
            dropdownMenu.querySelectorAll('.mm-dropdown-option').forEach(opt => opt.classList.remove('selected'));
            this.classList.add('selected');
        });
    });

    function selectFavorite(Id) {
        document.getElementById('selectedFavorite').value = Id;
    }

    function selectedNombre(Id) {
        document.getElementById('selectedNombre').value = Id;
    }

    function selectedNumdoc(Id) {
        document.getElementById('selectedNumdoc').value = Id;
    }

    document.getElementById('favoriteFilter').addEventListener('input', function() {
        filterOptions('favoriteFilter', 'favoriteButton');
    });

    document.getElementById('nombreFilter').addEventListener('input', function() {
        filterOptions('nombreFilter', 'nombreButton');
    });

    document.getElementById('nombrePayerFilter').addEventListener('input', function() {
        filterOptions('nombrePayerFilter', 'nombrePayerButton');
    });

    function filterOptions(filterId) {
        const filter = document.getElementById(filterId);
        const dropdownMenu = filter.parentElement;
        const filterValue = filter.value.toLowerCase();
        dropdownMenu.querySelectorAll('.mm-dropdown-option').forEach(option => {
            if (option.innerHTML.toLowerCase().includes(filterValue)) {
                option.style.display = '';
            } else {
                option.style.display = 'none';
            }
        });
    }

    function seleNumdoc(){
        var xselectedNumdoc = document.getElementById("selectedNumdoc").value;

        fetch(`/findfavorite/${xselectedNumdoc}`)
            .then(response => response.json())
            .then(jsondata => mostrarFavorito(jsondata))
    }

    function seleNom(){
        var xselectedNombre = document.getElementById("selectedNombre").value;

        fetch(`/findfavorite/${xselectedNombre}`)
            .then(response => response.json())
            .then(jsondata => mostrarFavorito(jsondata))
    }

    function seleNomPayer(){
        var xselectedNombrePayer = document.getElementById("selectedNombrePayer").value;

        fetch(`/findpayer/${xselectedNombrePayer}`)
            .then(response => response.json())
            .then(jsondata => mostrarPayer(jsondata))
    }

    function seleFav(){
        var xselectedFavorite = document.getElementById("selectedFavorite").value;

        fetch(`/findfavorite/${xselectedFavorite}`)
            .then(response => response.json())
            .then(jsondata => mostrarFavorito(jsondata))
    }

    function mostrarFavorito(jsondata){
        document.getElementById("document_id").value = jsondata[0].id;
        document.getElementById("doc_description").value = jsondata[0].doc_description;
        document.getElementById("numdoc").value = jsondata[0].numdoc;
        document.getElementById("account_holder").value = jsondata[0].account_holder;
        document.getElementById("bankname").value = jsondata[0].bankname;
        document.getElementById("account_number").value = jsondata[0].account_number;

        let favorito = jsondata[0].favorite;
        if (favorito == "Y"){
            $('#favorite').prop('checked', true);
        } else {
            $('#favorite').prop('checked', false);
        }

        $('#payer').focus();
    }

    function getSerial(xxid) {
        fetch(`/serial/${xxid}`)
            .then(response => response.json())
            .then(jsondata => showSerial(jsondata))
    }

    function showSerial(jsondata){
        let prefix = jsondata.prefix;

        document.getElementById("create_account_number").value = prefix;
        $('#create_account_number').focus();
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

        function grabarDoc(){
            var xseguir = true;
            var xtypedoc_id = document.getElementById("typedoc_id").value;
            if (xtypedoc_id.length < 1){
                xseguir = false;
                document.getElementById("typedoc_id_error").style.display = "block";
            }
            var xcreate_numdoc = document.getElementById("create_numdoc").value;
            if (xcreate_numdoc.length < 1){
                xseguir = false;
                document.getElementById("create_numdoc_error").style.display = "block";
            }
            var xcreate_account_holder = document.getElementById("create_account_holder").value;
            if (xcreate_account_holder.length < 1){
                xseguir = false;
                document.getElementById("create_account_holder_error").style.display = "block";
            }
            var xbank_id = document.getElementById("bank_id").value;
            if (xbank_id.length < 1){
                xseguir = false;
                document.getElementById("bank_id_error").style.display = "block";
            }
            var xcreate_account_number = document.getElementById("create_account_number").value;
            if (xcreate_account_number.length < 1){
                xseguir = false;
                document.getElementById("create_account_number_error").style.display = "block";
            }
            if (xseguir){
                storeDoc();
            }
        }

    function storeDoc(){
        var xtypedoc_id = document.getElementById("typedoc_id").value;
        var xcreate_numdoc = document.getElementById("create_numdoc").value;
        var xcreate_account_holder = document.getElementById("create_account_holder").value;
        var xbank_id = document.getElementById("bank_id").value;
        var xcreate_account_number = document.getElementById("create_account_number").value;

        fetch('/createdoc', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                typedoc_id: xtypedoc_id,
                numdoc: xcreate_numdoc,
                account_holder: xcreate_account_holder,
                bank_id: xbank_id,
                account_number: xcreate_account_number,
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'error') {
                document.getElementById("create_account_number_exist").style.display = "block";
            } else if (data.status === 'success') {
                // Accediendo al primer (y único) objeto dentro del array 'data'
                let record = data.data[0];

                document.getElementById("document_id").value = record.id;
                document.getElementById("doc_description").value = record.doc_description;
                document.getElementById("numdoc").value = record.numdoc;
                document.getElementById("account_holder").value = record.account_holder;
                document.getElementById("bankname").value = record.bankname;
                document.getElementById("account_number").value = record.account_number;

                $('#myModal').modal('hide');
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }

    function crearDoc(){
        var xcountry2_id = document.getElementById("country2_id").value;

        let selectTypedoc = document.getElementById('typedoc_id');
        selectTypedoc.innerHTML = ''; // Limpiar contenido anterior

        // Crear y añadir una opción por defecto
        let defaultOption = document.createElement('option');
        defaultOption.value = '';
        defaultOption.text = 'Seleccionar';
        selectTypedoc.appendChild(defaultOption);

        fetch(`/typedoc2/${xcountry2_id}`)
            .then(response => response.json())
            .then(jsondata => cargaTypedoc(jsondata))
    }

    function cargaTypedoc(typedocs) {
        let selectTypedoc = document.getElementById('typedoc_id');

        // Recorrer el array y crear opciones
        typedocs.forEach(typedoc => {
            let option = document.createElement('option');
            option.value = typedoc.id;  // Asumiendo que el id está en la propiedad 'id'
            option.text = typedoc.description;  // Asumiendo que el nombre del estado está en la propiedad 'name'
            selectTypedoc.appendChild(option);
        });

        getBanco()
    }

    function getBanco(){
        var xcountry2_id = document.getElementById("country2_id").value;

        let selectBank = document.getElementById('bank_id');
        selectBank.innerHTML = ''; // Limpiar contenido anterior

        // Crear y añadir una opción por defecto
        let defaultOption = document.createElement('option');
        defaultOption.value = '';
        defaultOption.text = 'Seleccionar';
        selectBank.appendChild(defaultOption);

        fetch(`/bank2/${xcountry2_id}`)
            .then(response => response.json())
            .then(jsondata => cargaBank(jsondata))
    }

    function cargaBank(banks) {
        let selectBank = document.getElementById('bank_id');

        // Recorrer el array y crear opciones
        banks.forEach(bank => {
            let option = document.createElement('option');
            option.value = bank.id;  // Asumiendo que el id está en la propiedad 'id'
            option.text = bank.bankname;  // Asumiendo que el nombre del estado está en la propiedad 'name'
            selectBank.appendChild(option);
        });
    }

    function getDescripcion(xxid) {
        fetch(`/description/${xxid}`)
            .then(response => response.json())
            .then(jsondata => showDescripcion(jsondata))
    }

    function showDescripcion(jsondata){
        let conversion_description = jsondata.conversion_description;
        let conversion_value = jsondata.conversion_value;
        let reference_conversion_value = jsondata.reference_conversion_value;
        let currency_description = jsondata.currency_description;
        let currency = jsondata.currency;
        let currency2 = jsondata.currency2;
        let currency3 = jsondata.currency3;
        let symbol = jsondata.symbol;
        let symbol2 = jsondata.symbol2;
        let symbol3 = jsondata.symbol3;
        let country2_id = jsondata.country2_id;
        let phonecode = jsondata.phone_code;
        let two_decimals = jsondata.two_decimals;
        let data1 = jsondata.canawil_banks;
        let data2 = jsondata.way_to_pays;

        let selectCanawilBank = document.getElementById('canawilbank_id');
        selectCanawilBank.innerHTML = ''; // Limpiar contenido anterior

        // Crear y añadir una opción por defecto
        let defaultOption = document.createElement('option');
        defaultOption.value = '';
        defaultOption.text = 'Seleccionar';
        selectCanawilBank.appendChild(defaultOption);

        let selectWaytoPay = document.getElementById('waytopay_id');
        selectWaytoPay.innerHTML = ''; // Limpiar contenido anterior

        // Crear y añadir una opción por defecto
        let defaultOption2 = document.createElement('option');
        defaultOption2.value = '';
        defaultOption2.text = 'Seleccionar';
        selectWaytoPay.appendChild(defaultOption2);

        // Recorrer el array y crear opciones
        data1.forEach(bank => {
            let option = document.createElement('option');
            option.value = bank.id;  // Asumiendo que el id está en la propiedad 'id'
            option.text = bank.bank_name;  // Asumiendo que el nombre del estado está en la propiedad 'name'
            selectCanawilBank.appendChild(option);
        });

        data2.forEach(way => {
            let option2 = document.createElement('option');
            option2.value = way.id;  // Asumiendo que el id está en la propiedad 'id'
            option2.text = way.description;  // Asumiendo que el nombre del estado está en la propiedad 'name'
            selectWaytoPay.appendChild(option2);
        });

        document.getElementById("phonecode").value = phonecode;
        document.getElementById("country2_id").value = country2_id;
        document.getElementById("symbol2").value = symbol2;
        document.getElementById("symbol3").value = symbol3;
        document.getElementById("conversion_description").value = conversion_description;
        if (two_decimals === 'Y'){
            document.getElementById("conversion_value").value = formatearConComaDecimal(parseFloat(conversion_value)) + ' ' + currency;
            document.getElementById("reference_conversion_value").value = formatearConComaDecimal(parseFloat(reference_conversion_value)) + ' ' + symbol2;
            document.getElementById("real_conversion_value").value = parseFloat(conversion_value).toFixed(2);
            document.getElementById("real_reference_conversion_value").value = parseFloat(reference_conversion_value).toFixed(2);
        } else {
            document.getElementById("conversion_value").value = formatFloat(conversion_value) + ' ' + currency;
            document.getElementById("real_conversion_value").value = conversion_value;
            document.getElementById("reference_conversion_value").value = formatFloat(reference_conversion_value) + ' ' + symbol2;
            document.getElementById("real_reference_conversion_value").value = reference_conversion_value;
        }
        $('#mount_value').attr('placeholder', currency_description);
        $('#label_mount_value').text('Monto a cambiar ' + currency + ' (*):');
        $('#label_conversion_value').text('Tasa de cambio ' + currency2 + ':');
        $('#label_mount_change').text('Monto a pagar en ' + currency2 + ':');
        $('#label_reference_conversion_value').text('Tasa Ref. ' + currency3 + ':');
        $('#label_mount_reference').text('Monto en ' + currency3 + ':');
        $('#mount_value').focus();
    }

    function calcular(xvalor){
        var xsymbol2 = document.getElementById("symbol2").value;
        var xsymbol3 = document.getElementById("symbol3").value;
        var xconversion_value = parseFloat(document.getElementById("real_conversion_value").value);
        var xreference_conversion_value = parseFloat(document.getElementById("real_reference_conversion_value").value);

        // Calcular el monto de cambio sin formato
        var mountChangeValue = (xvalor / xconversion_value).toFixed(2);

        // Formatear el valor calculado para mostrarlo
        var mountChangeFormatted = formatearNumeroSimple(mountChangeValue) + ' ' + xsymbol2;
        document.getElementById("mount_change").value = mountChangeFormatted;
        document.getElementById("mount_change2").value = mountChangeValue;

        // Calcular el monto de referencia sin formato
        var mountReferenceValue = (mountChangeValue / xreference_conversion_value).toFixed(2);

        // Formatear el valor calculado para mostrarlo
        var mountReferenceFormatted = formatearNumeroSimple(mountReferenceValue) + ' ' + xsymbol3;
        document.getElementById("mount_reference").value = mountReferenceFormatted;
        document.getElementById("mount_reference2").value = mountReferenceValue;
    }

    function formatFloat(value) {
        // Convierte el valor a un número flotante
        let num = parseFloat(value);

        // Si todos los decimales son 0, muestra solo dos decimales
        if (Number.isInteger(num)) {
            return num.toFixed(2).replace('.', ',').replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }

        // Convierte el número a cadena para formatearlo
        let parts = num.toString().split('.'); // Divide en parte entera y parte decimal

        // Añade separadores de miles a la parte entera
        parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, '.');

        // Si hay parte decimal, reemplaza el punto por coma y elimina ceros finales
        if (parts[1]) {
            parts[1] = parts[1].replace(/0+$/, ''); // Elimina ceros finales
            return parts.join(','); // Une la parte entera con la decimal usando una coma
        }

        return parts.join(',');
    }

    function formatearConComaDecimal(numero) {
        // Convertir el número a string y separar la parte entera de la decimal
        let partes = numero.toFixed(2).split('.');

        // Aplicar formato a la parte entera
        let parteEntera = partes[0].replace(/\B(?=(\d{3})+(?!\d))/g, '.');

        // Formar el número con la coma decimal
        return parteEntera + ',' + partes[1];
    }

    function searchDoc(){
        var xseguir = true;
        var xsearch_document = document.getElementById("search_document").value;
        if (xsearch_document.length < 1){
            xseguir = false;
            document.getElementById("search_document_error3").style.display = "block";
        }
        if (xseguir){
            var xopcion = document.querySelector('input[name="inlineRadioOptions"]:checked').value;
            switch(xopcion){
                case 'option1':
                    searchNumeroCuenta(xsearch_document);
                    break;
                case 'option2':
                    searchNumeroDoc(xsearch_document);
                    break;
                case 'option3':
                    searchNombre(xsearch_document);
                    break;
            }
        }
    }

    function searchNumeroCuenta(xnumcuenta){
        fetch(`/findnumcuenta/${xnumcuenta}`)
            .then(response => response.json())
            .then(data => {
                if (data.status === 'error') {
                    document.getElementById("search_document_error1").style.display = "block";
                    $('#search_document').focus();
                } else if (data.status === 'success') {search_document
                    // Accediendo al primer (y único) objeto dentro del array 'data'
                    let record = data.data[0];

                    document.getElementById("document_id").value = record.id;
                    document.getElementById("doc_description").value = record.doc_description;
                    document.getElementById("numdoc").value = record.numdoc;
                    document.getElementById("account_holder").value = record.account_holder;
                    document.getElementById("bankname").value = record.bankname;
                    document.getElementById("account_number").value = record.account_number;

                    let favorito = record.favorite;
                    if (favorito == "Y"){
                        $('#favorite').prop('checked', true);
                    } else {
                        $('#favorite').prop('checked', false);
                    }

                    $('#payer').focus();
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
    }

    function searchNumeroDoc(xnumdoc){
        fetch(`/findnumdoc/${xnumdoc}`)
            .then(response => response.json())
            .then(jsondata => showNumdoc(jsondata))
    }

    function showNumdoc(jsondata){
        let objState_id = document.getElementById('numdocButton');
        clearSelect(objState_id);

        jsondata.forEach(element => {
            let optionTag = document.createElement('a');
            optionTag.href = "#";
            optionTag.className = "mm-dropdown-option";
            optionTag.innerHTML = element.bankname + ' Cuenta: ' + element.account_number;
            optionTag.addEventListener('click', function(event) {
                event.preventDefault();
                objState_id.querySelectorAll('.mm-dropdown-option').forEach(opt => opt.classList.remove('selected'));
                this.classList.add('selected');
                document.getElementById('selectedNumdoc').value = element.id;
            });

            objState_id.appendChild(optionTag);
        });

        $('#myModal4').modal('show');
    }

    function searchNombre(xnombre){
        fetch(`/findnombre/${xnombre}`)
            .then(response => response.json())
            .then(jsondata => showNombre(jsondata))
    }

    function showNombre(jsondata){
        let objState_id = document.getElementById('nombreButton');
        clearSelect(objState_id);

        // Añadir el campo de entrada para filtrar
        let filterInput = document.createElement('input');
        filterInput.type = 'text';
        filterInput.id = 'nombreFilter';
        filterInput.className = 'mm-dropdown-filter';
        filterInput.placeholder = 'Buscar';
        filterInput.addEventListener('input', function() {
            filterOptions('nombreFilter');
        });
        objState_id.appendChild(filterInput);

        jsondata.forEach(element => {
            let optionTag = document.createElement('a');
            optionTag.href = "#";
            optionTag.className = "mm-dropdown-option";
            optionTag.innerHTML = element.complete_description;
            optionTag.addEventListener('click', function(event) {
                event.preventDefault();
                objState_id.querySelectorAll('.mm-dropdown-option').forEach(opt => opt.classList.remove('selected'));
                this.classList.add('selected');
                document.getElementById('selectedNombre').value = element.id;
            });

            objState_id.appendChild(optionTag);
        });

        $('#myModal3').modal('show');
    }

    function clearSelect(select) {
        while (select.firstChild) {
            select.removeChild(select.firstChild);
        }
    }

    function procesarValor(input) {
        let valorActual = input.value.replace(/\./g, '').replace(',', '.');

        // Convertimos el valor a un número flotante para realizar cálculos
        let numeroSinFormato = parseFloat(valorActual);

        document.getElementById('real_mount_value').value = numeroSinFormato;

        // Realizamos el cálculo con el valor sin formato
        calcular(numeroSinFormato);

        // Aplicamos la máscara al valor del input
        formatearNumero(input);
    }

    function formatearNumero(input) {
        // Guardar la posición del cursor
        let cursorPosition = input.selectionStart;

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

        // Restaurar la posición del cursor
        input.setSelectionRange(cursorPosition, cursorPosition);
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

        function getAccount(xid){
        fetch(`/account/${xid}`)
            .then(response => response.json())
            .then(jsondata => showAccount(jsondata))
    }

    function showAccount(jsondata){
        let account_number = jsondata.account_number;

        if (account_number.length > 4) {
            // Obtener los últimos 4 dígitos
            let ultimos4 = account_number.slice(-4);
            // Reemplazar el resto con "x"
            let enmascarado = "X".repeat(account_number.length - 4) + ultimos4;
            // Mostrar el valor enmascarado en el input
            document.getElementById("canawil_account_number").value = enmascarado;
        }
    }

    function getWay(xid){
        fetch(`/way/${xid}`)
            .then(response => response.json())
            .then(jsondata => showWay(jsondata))
    }

    function showWay(jsondata){
        let reference = jsondata.reference;

        $("#label_reference").text(reference + ':');
    }

    function validar(){
        var xseguir = true;
        var xconversion_id = document.getElementById("conversion_id").value;
        if (xconversion_id.length < 1){
            xseguir = false;
            document.getElementById("conversion_id_error").style.display = "block";
        }
        var xmount_value = document.getElementById("mount_value").value;
        if (xmount_value.length < 1){
            xseguir = false;
            document.getElementById("mount_value_error").style.display = "block";
        }
        var xdoc_description = document.getElementById("doc_description").value;
        if (xdoc_description.length < 1){
            xseguir = false;
            document.getElementById("payer_error2").style.display = "block";
        }
        var xcanawilbank_id = document.getElementById("canawilbank_id").value;
        if (xcanawilbank_id.length < 1){
            xseguir = false;
            document.getElementById("canawilbank_id_error").style.display = "block";
        }
        var xwaytopay_id = document.getElementById("waytopay_id").value;
        if (xwaytopay_id.length < 1){
            xseguir = false;
            document.getElementById("waytopay_id_error").style.display = "block";
        }
        if (xseguir){
            let checkbox = document.getElementById('favorite');
            // Verificar si el checkbox está marcado
            if (checkbox.checked) {
                document.getElementById("favorite_value").value = 'Y';
            } else {
                document.getElementById("favorite_value").value = 'N';
            }

            document.view.submit();
        }
    }
</script>
@stop
