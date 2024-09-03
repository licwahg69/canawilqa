@extends('adminlte::page')

@section('title', 'Datos de la Transacción')

@section('content_header')
    <h1 class="m-0 text-primary text-center"><b>Datos de la Transacción: <label style="color:black">{{$transactions[0]->complete_description}}</label></b></h1>
@stop

@section('content')
<form action="/transaction" method="POST" id="view" name="view" class="formeli">
    @csrf
    <input type="hidden" id="transaction_id" name="transaction_id" value="{{$transactions[0]->id}}">
    <input type="hidden" id="type_screen" name="type_screen" value="">
    <input type="hidden" id="payer_cellphone" name="payer_cellphone" value="{{$transactions[0]->cellphone}}">
    <input type="hidden" id="toaction" name="toaction" value="save_transfer">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-8 form-group text-left">
                            <br>
                            <i class="fas fa-cash-register"></i> <b> Aliado o Usuario: {{$transactions[0]->comercial_name}}
                                @switch($transactions[0]->sendstatus)
                                    @case('ENV')
                                        <label class="text-primary">
                                        @break
                                    @case('REC')
                                        <label class="text-orange">
                                        @break
                                    @case('PRO')
                                        <label class="text-cyan">
                                        @break
                                    @case('TRA')
                                        <label class="text-danger">
                                        @break
                                @endswitch
                                (Estatus: <i class="fas fa-traffic-light"></i> {{$transactions[0]->sendstatus_text}})</label>
                            </b>
                        </div>
                        <div class="col-md-4 form-group text-right">
                            <br>
                            <b>
                                Fecha de la transacción: {{date('d-m-Y',strtotime($transactions[0]->send_date))}}
                            </b>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-2 form-group">
                            <label for="transaction_id" class="form-label"><b>ID Transacción:</b></label>
                            <input disabled class="form-control height" type="text" id="transaction_id" name="transaction_id" value="{{$transactions[0]->id}}">
                        </div>
                        <div class="col-md-3 form-group">
                            <label for="conversion_id" class="form-label"><b>Tipo de cambio:</b></label>
                            <input disabled class="form-control height" type="text" id="conversion_id" name="conversion_id" value="{{$transactions[0]->a_to_b}}">
                        </div>
                        <div class="col-md-7 form-group">
                            <label for="conversion_description" class="form-label"><b>Descripción:</b></label>
                            <input disabled class="form-control height" type="text" id="conversion_description" name="conversion_description" value="{{$transactions[0]->conversion_description}}">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3 form-group">
                            <label for="mount_value" id="label_mount_value">Monto a cambiar:</label>
                            <input disabled type="text" class="form-control text-right" id="mount_value" name="mount_value" value="{{ trim($transactions[0]->mount_value_fm) }}{{ trim($transactions[0]->symbol) }} {{ trim($transactions[0]->currency) }}">
                        </div>
                        <div class="col-md-2 form-group">
                            <label for="conversion_value" id="label_conversion_value">Tasa de cambio {{$transactions[0]->currency2}}:</label>
                            <input disabled type="text" class="form-control text-right" style="color: red; background-color: white" id="conversion_value" name="conversion_value" value="{{$transactions[0]->two_decimals == 'Y' ? number_format($transactions[0]->conversion_value, 2).' '.$transactions[0]->currency : $transactions[0]->conversion_value.' '.$transactions[0]->currency}}">
                        </div>
                        <div class="col-md-3 form-group">
                            <label for="mount_change" id="label_mount_change">Monto a pagar {{$transactions[0]->currency2}}:</label>
                            <input disabled type="text" class="form-control text-right" style="color:darkgreen; background-color: white" id="mount_change" name="mount_change" value="{{ trim($transactions[0]->mount_change_fm).' '.$transactions[0]->symbol2 }}">
                        </div>
                        <div class="col-md-2 form-group">
                            <label for="reference_conversion_value" id="label_reference_conversion_value">Tasa Ref. {{$transactions[0]->currency3}}:</label>
                            <input disabled type="text" class="form-control text-right" style="color: red; background-color: white" id="reference_conversion_value" name="reference_conversion_value" value="{{$transactions[0]->two_decimals == 'Y' ? number_format($transactions[0]->reference_conversion_value, 2).' '.$transactions[0]->currency2 : $transactions[0]->reference_conversion_value.' '.$transactions[0]->currency2}}">
                        </div>
                        <div class="col-md-2 form-group">
                            <label for="mount_reference" id="label_mount_reference">Monto Ref. {{$transactions[0]->currency3}}:</label>
                            <input disabled type="text" class="form-control text-right" style="color: darkgreen; background-color: white" id="mount_reference" name="mount_reference" value="{{ trim($transactions[0]->mount_reference_fm).' '.$transactions[0]->symbol3 }}">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label for="doc_description" class="form-label"><b>Tipo de Documento:</b></label>
                            <input disabled class="form-control height" type="text" id="doc_description" name="doc_description" value="{{$transactions[0]->doc_description}}">
                        </div>
                        <div class="col-md-3 form-group">
                            <label for="numdoc" class="form-label"><b>Número Documento ID:</b></label>
                            <input disabled class="form-control height" type="text" id="numdoc" name="numdoc" value="{{$transactions[0]->numdoc}}">
                        </div>
                        <div class="col-md-5 form-group">
                            <label for="account_holder" class="form-label"><b>Nombre del Titular:</b></label>
                            <input disabled class="form-control height" type="text" id="account_holder" name="account_holder" value="{{$transactions[0]->account_holder}}">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-8 form-group">
                            <label for="bankname" class="form-label"><b>Banco:</b></label>
                            <input disabled class="form-control height" type="text" id="bankname" name="bankname" value="{{$transactions[0]->bankname}}">
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="account_number" class="form-label"><b>Número de cuenta:</b></label>
                            <input disabled class="form-control height" type="text" id="account_number" name="account_number" value="{{$transactions[0]->account_number}}">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-8 form-group">
                            <label for="payer" class="form-label"><b>Nombre del pagador:</b></label>
                            <input disabled class="form-control" type="text" id="payer" name="payer" value="{{$transactions[0]->payer_name}}">
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="cellphone" class="form-label"><b>Celular:</b></label>
                            <div class="input-group mb-3">
                                <span class="input-group-text height"><i class="bi bi-whatsapp"></i></span>
                                <span class="input-group-text height" id="spancodigo">{{ $phone_code }}</span>
                                <input disabled class="form-control" type="text" id="cellphone" name="cellphone" maxlength=15 value="{{$onlycellphone}}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label for="canawil_bank_name" class="form-label"><b>{{ trim($transactions[0]->mount_value_fm) }}{{ trim($transactions[0]->symbol) }} {{ trim($transactions[0]->currency) }} transferidos a:</b></label>
                            <input disabled class="form-control" type="text" id="canawil_bank_name" name="canawil_bank_name" value="{{$transactions[0]->canawil_bank_name}}">
                        </div>
                        <div class="col-md-3 form-group">
                            <label for="canawil_account_number" class="form-label"><b>Cuenta:</b></label>
                            <input disabled class="form-control" type="text" id="canawil_account_number" name="canawil_account_number" value="{{$transactions[0]->canawil_account_number}}">
                        </div>
                        <div class="col-md-3 form-group">
                            <label for="waytopay_description" class="form-label"><b>Medio usado para el envío:</b></label>
                            <input disabled class="form-control" type="text" id="waytopay_description" name="waytopay_description" value="{{$transactions[0]->waytopay_description}}">
                        </div>
                        <div class="col-md-2 form-group">
                            <label for="waytopay_reference" class="form-label"><b>{{$transactions[0]->reference_text}}:</b></label>
                            <input disabled class="form-control" type="text" id="waytopay_reference" name="waytopay_reference" value="{{$transactions[0]->waytopay_reference}}">
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header"><i class="fas fa-camera"></i>
                            <b> Foto de la transacción bancaria</b>
                        </div>
                        <div class="card-body">
                            <div class="panel-body text-center" id='imgfoto'>
                                <div class="row">
                                    <div class="col-md-12 form-group text-center" id="foto_web" style='display: none;'>
                                        @switch($transactions[0]->transaction_image_orientation)
                                            @case('VER')
                                                <img width='350' height='600' alt="" id="imagen" style="max-width: 100%; max-height: 100%;" src="{{$transactions[0]->bank_image}}" />
                                                @break
                                            @case('CUA')
                                                <img width='400' height='400' alt="" id="imagen" style="max-width: 100%; max-height: 100%;" src="{{$transactions[0]->bank_image}}" />
                                                @break
                                            @case('HOR')
                                                <img width='600' height='300' alt="" id="imagen" style="max-width: 100%; max-height: 100%;" src="{{$transactions[0]->bank_image}}" />
                                                @break
                                        @endswitch
                                    </div>
                                    <div class="col-md-12 form-group text-center" id="foto_mobile" style='display: none;'>
                                        @switch($transactions[0]->transaction_image_orientation)
                                            @case('VER')
                                                <img width='350' height='600' alt="" id="imagen" style="max-width: 100%; max-height: 100%;" src="{{$transactions[0]->bank_image}}" />
                                                @break
                                            @case('CUA')
                                                <img width='400' height='400' alt="" id="imagen" style="max-width: 100%; max-height: 100%;" src="{{$transactions[0]->bank_image}}" />
                                                @break
                                            @case('HOR')
                                                <img width='500' height='200' alt="" id="imagen" style="max-width: 100%; max-height: 100%;" src="{{$transactions[0]->bank_image}}" />
                                                @break
                                        @endswitch
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-dollar-sign"></i> <b>Datos de la transferencia</b>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-5 form-group">
                            <label for="canawilbank_id" class="form-label"><b>Transferir fondos desde el banco Canawil (*):</b></label>
                            <select id="canawilbank_id" name="canawilbank_id" class="form-control" onclick="quitaMensaje()">
                                <option value="">Seleccionar</option>
                                @foreach ($canawil_banks as $canawil_bank)
                                    <option value="{{$canawil_bank->id}}">{{$canawil_bank->bank_name}} {{$canawil_bank->account_number}}</option>
                                @endforeach
                            </select>
                            <div id="canawilbank_id_error" class="talert" style='display: none;'>
                                <p class="text-danger">El banco de Canawil es requerido</p>
                            </div>
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="waytopay_id" class="form-label"><b>Tipo de pago a usar (*):</b></label>
                            <select id="waytopay_id" name="waytopay_id" class="form-control" onchange="getWay(this.value)" onclick="quitaMensaje()">
                                <option value="">Seleccionar</option>
                                @foreach ($way_to_pays as $way_to_pay)
                                    <option value="{{$way_to_pay->id}}">{{$way_to_pay->description}}</option>
                                @endforeach
                            </select>
                            <div id="waytopay_id_error" class="talert" style='display: none;'>
                                <p class="text-danger">El Tipo de pago a usar es requerido</p>
                            </div>
                        </div>
                        <div class="col-md-3 form-group">
                            <label for="waytopay_reference" class="form-label" id="label_reference"><b>Referencia:</b></label>
                            <input class="form-control" type="text" id="waytopay_reference" name="waytopay_reference" value="" placeholder="Referencia">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 form-group">
                            <label for="whatsapp_message">Puede enviar un mensaje de WhatsApp al pagador de esta transacción si lo desea:</label>
                            <textarea class="form-control" id="whatsapp_message" name="whatsapp_message" onkeypress="quitaMensaje()" maxlength="250"
                                placeholder="Utilice un máximo de 250 caracteres para el contenido del mensaje"
                                tabindex="2" rows="2">{{ old('whatsapp_message') }}</textarea>
                            <div id="whatsapp_message_error" class="talert" style='display: none;'>
                                <p class="text-danger">El mensaje no puede estar en blanco</p>
                            </div>
                            <div id="payer_cellphone_error" class="talert" style='display: none;'>
                                <p class="text-danger">El celular del pagador no puede estar en blanco</p>
                            </div>
                        </div>
                        <div class="col-md-3 form-group text-center">
                            <button type="button" id="buttongrabar" onclick="sendMessage()" class="btn btn-success btn-block">Enviar Mensaje <i class="bi bi-whatsapp"></i></button>
                        </div>
                    </div>
                    <br>
                    <div class="col-md-12 form-group">
                        <b>(*) Campos obligatorios</b>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-2 form-group text-center">
                            <button type="button" id="buttongrabar" onclick="validar()" class="btn btn-primary btn-block">Guardar  <i class="fa fa-save"></i></button>
                        </div>
                        <div class="col-md-2 form-group text-center">
                            <a href="/transaction" class="btn btn-danger btn-block">Cancelar  <i class="fa fa-arrow-circle-left"></i></a>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 form-group">
                            <label style="color: red; font-weight:bold; font-size:20px"><b>Nota: Tome un capture de pantalla de la transacción bancaria hecha para transferir el pago y tengala a la mano. Le será solicitada.</b></label>
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
    document.addEventListener('DOMContentLoaded', function() {
        if (window.innerWidth > 768) {
            document.getElementById("type_screen").value = "W";
            document.getElementById("foto_web").style.display = "block";
            document.getElementById("foto_mobile").style.display = "none";
        } else {
            document.getElementById("type_screen").value = "M";
            document.getElementById("foto_web").style.display = "none";
            document.getElementById("foto_mobile").style.display = "block";
        }
    });
</script>
<script>
    function quitaMensaje(){
        $(".talert").css("display", "none");
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

    function sendMessage() {
        var xseguir = true;
        var xmessage = document.getElementById("whatsapp_message").value;
        if (xmessage.length < 1){
            xseguir = false;
            document.getElementById("whatsapp_message_error").style.display = "block";
        }
        var telefono = document.getElementById("payer_cellphone").value;
        if (telefono.length < 1){
            xseguir = false;
            document.getElementById("payer_cellphone_error").style.display = "block";
        }
        if (xseguir){
            var mensaje = encodeURIComponent(xmessage);

            // Crear la URL para enviar el mensaje
            var url = `https://api.whatsapp.com/send?phone=${telefono}&text=${mensaje}`;

            // Abrir la URL en una nueva ventana sin refrescar la página actual
            window.open(url, '_blank');
        }
    }

    function validar(){
        var xseguir = true;
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
            document.view.submit();
        }
    }
</script>
@stop
