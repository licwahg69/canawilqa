@extends('adminlte::page')

@section('title', 'Datos de la Transacción')

@section('content_header')
    <h1 class="m-0 text-primary text-center"><b>Datos de la Transacción: <label style="color:black">{{$transactions[0]->complete_description}}</label></b></h1>
@stop

@section('content')
<form action="/transaction" method="POST" id="view" name="view" class="formeli" enctype="multipart/form-data">
    @csrf
    <input type="hidden" id="transaction_id" name="transaction_id" value="{{$transactions[0]->id}}">
    <input type="hidden" id="amount_rest" name="amount_rest" value="{{$amount_rest}}">
    <input type="hidden" id="type_screen" name="type_screen" value="">
    <input type="hidden" id="orientation" name="orientation" value="">
    <input type="hidden" id="origin" name="origin" value="{{$origin}}">
    <input type="hidden" id="payer_cellphone" name="payer_cellphone" value="{{$transactions[0]->cellphone}}">
    <input type="hidden" id="toaction" name="toaction" value="photo_trans">
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
                            <label for="mount_value" id="label_mount_value">Monto recibido:</label>
                            <input disabled type="text" class="form-control text-right" id="mount_value" name="mount_value" value="{{ trim($transactions[0]->mount_value_fm) }}{{ trim($transactions[0]->symbol) }} {{ trim($transactions[0]->currency) }}">
                        </div>
                        <div class="col-md-2 form-group">
                            <label for="conversion_value" id="label_conversion_value">Tasa de cambio {{$transactions[0]->currency2}}:</label>
                            <input disabled type="text" class="form-control text-right" style="color: red; background-color: white" id="conversion_value" name="conversion_value" value="{{$transactions[0]->two_decimals == 'Y' ? number_format($transactions[0]->conversion_value,2,',','.').' '.$transactions[0]->currency : $transactions[0]->conversion_value.' '.$transactions[0]->currency}}">
                        </div>
                        <div class="col-md-3 form-group">
                            <label for="mount_change" id="label_mount_change">Monto a pagar {{$transactions[0]->currency2}}:</label>
                            <input disabled type="text" class="form-control text-right" style="color:darkgreen; background-color: white" id="mount_change" name="mount_change" value="{{ trim($transactions[0]->mount_change_fm).' '.$transactions[0]->symbol2 }}">
                            <input type="hidden" id="real_mount_change" name="real_mount_change" value="{{trim($transactions[0]->mount_change)}}">
                        </div>
                        <div class="col-md-2 form-group">
                            <label for="reference_conversion_value" id="label_reference_conversion_value">Tasa Ref. {{$transactions[0]->currency3}}:</label>
                            <input disabled type="text" class="form-control text-right" style="color: red; background-color: white" id="reference_conversion_value" name="reference_conversion_value" value="{{$transactions[0]->two_decimals == 'Y' ? number_format($transactions[0]->reference_conversion_value,2,',','.').' '.$transactions[0]->currency2 : $transactions[0]->reference_conversion_value.' '.$transactions[0]->currency2}}">
                        </div>
                        <div class="col-md-2 form-group">
                            <label for="mount_reference" id="label_mount_reference">Monto Ref. {{$transactions[0]->currency3}}:</label>
                            <input disabled type="text" class="form-control text-right" style="color: darkgreen; background-color: white" id="mount_reference" name="mount_reference" value="{{ trim($transactions[0]->mount_reference_fm).' '.$transactions[0]->symbol3 }}">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3 form-group">
                            <label for="amount_withheld" class="form-label" id="label_amount_withheld"><b>Retenido en {{ trim($transactions[0]->currency) }}:</b> </label>
                            <input disabled class="form-control text-right" style="color:blue; background-color: white" type="text" id="amount_withheld" name="amount_withheld" value="{{$transactions[0]->amount_withheld_fm}}{{ trim($transactions[0]->symbol)}}" placeholder="Retención">
                        </div>
                        <div class="col-md-3 form-group">
                            <label for="net_amount" id="label_net_amount">{{$transactions[0]->credit == 'Y' ? 'Pendiente por recibir ' : 'Monto recibido '}} en {{ trim($transactions[0]->currency) }}:</label>
                            <input disabled type="text" class="form-control text-right" style="color:darkgreen; background-color: white" id="net_amount" name="net_amount" value="{{trim($transactions[0]->net_amount_fm)}}{{ trim($transactions[0]->symbol) }}" placeholder="Monto a enviar">
                            <input type="hidden" id="net_amount2" name="net_amount2" value="">
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
                    @if ($transactions[0]->credit == 'N')
                        <div class="row">
                            <div class="col-md-4 form-group">
                                <b><label for="canawil_bank_name" class="form-label" style="color:blue">{{ trim($transactions[0]->net_amount_fm) }}{{ trim($transactions[0]->symbol) }} {{ trim($transactions[0]->currency) }}</label> transferidos a:</b>
                                <input disabled class="form-control" type="text" id="canawil_bank_name" name="canawil_bank_name" value="{{$transactions[0]->canawil_bank_name}}">
                            </div>
                            <div class="col-md-4 form-group">
                                <label for="canawil_account_number" class="form-label"><b>Cuenta:</b></label>
                                <input disabled class="form-control" type="text" id="canawil_account_number" name="canawil_account_number" value="{{$transactions[0]->canawil_account_number}}">
                            </div>
                            <div class="col-md-4 form-group">
                                <label for="send_way" class="form-label"><b>Medio para el envío:</b></label>
                                <input disabled class="form-control" type="text" id="send_way" name="send_way" value="{{$transactions[0]->reference_text}}" placeholder="Medio para el envío">
                            </div>
                        </div>
                    @endif
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
                                                <img width='350' height='600' alt="" id="imagen2" style="max-width: 100%; max-height: 100%;" src="{{$transactions[0]->bank_image}}" />
                                                @break
                                            @case('CUA')
                                                <img width='400' height='400' alt="" id="imagen2" style="max-width: 100%; max-height: 100%;" src="{{$transactions[0]->bank_image}}" />
                                                @break
                                            @case('HOR')
                                                <img width='600' height='300' alt="" id="imagen2" style="max-width: 100%; max-height: 100%;" src="{{$transactions[0]->bank_image}}" />
                                                @break
                                        @endswitch
                                    </div>
                                    <div class="col-md-12 form-group text-center" id="foto_mobile" style='display: none;'>
                                        @switch($transactions[0]->transaction_image_orientation)
                                            @case('VER')
                                                <img width='350' height='600' alt="" id="imagen2" style="max-width: 100%; max-height: 100%;" src="{{$transactions[0]->bank_image}}" />
                                                @break
                                            @case('CUA')
                                                <img width='400' height='400' alt="" id="imagen2" style="max-width: 100%; max-height: 100%;" src="{{$transactions[0]->bank_image}}" />
                                                @break
                                            @case('HOR')
                                                <img width='500' height='200' alt="" id="imagen2" style="max-width: 100%; max-height: 100%;" src="{{$transactions[0]->bank_image}}" />
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
                        @if ($amount_rest > 0)
                            <div class="col-md-6 form-group">
                                <div class="row">
                                    <div class="col-md-12 form-group">
                                        <label for="currencybank_id" class="form-label"><b>Transferir desde el banco destino (*):</b></label>
                                        <select id="currencybank_id" name="currencybank_id" class="form-control" onchange="getAvailable(this.value)" onclick="quitaMensaje()">
                                            <option value="">Seleccionar</option>
                                            @foreach ($currency_banks as $currency_bank)
                                                <option value="{{$currency_bank->id}}">{{$currency_bank->bankname}} {{$currency_bank->account_number}}</option>
                                            @endforeach
                                        </select>
                                        <div id="currencybank_id_error" class="talert" style='display: none;'>
                                            <p class="text-danger">El banco destino es requerido</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 form-group">
                                        <label for="available_amount" id="label_available_amount">Monto disponible en {{$transactions[0]->currency2}}({{$transactions[0]->symbol2}}):</label>
                                        <input disabled type="text" class="form-control text-right" style="color:darkgreen; background-color: white" id="available_amount" name="available_amount" value="" placeholder="Monto disponible">
                                        <input type="hidden" id="real_available_amount" name="real_available_amount" value="">
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label for="amount" id="label_amount">Transferir en {{$transactions[0]->currency2}}({{$transactions[0]->symbol2}}) (*):</label>
                                        <input type="text" class="form-control text-right" style="color:darkgreen; background-color: white" id="amount" name="amount" value="{{number_format(trim($sumamount_rest), 2, ',', '.')}}" oninput="procesarValor(this)" onkeypress="quitaMensaje()" placeholder="Monto a transferir">
                                        <input type="hidden" id="real_amount" name="real_amount" value="{{$sumamount_rest}}">
                                    </div>
                                    <div id="amount_error" class="talert" style='display: none;'>
                                        <p class="text-danger">El Monto a transferir es requerido</p>
                                    </div>
                                    <div id="amount_error2" class="talert" style='display: none;'>
                                        <p class="text-danger">El Monto a transferir no puede ser mayor que el monto disponible</p>
                                    </div>
                                    <div id="amount_error3" class="talert" style='display: none;'>
                                        <p class="text-danger">El Monto a transferir no puede ser mayor que la totalidad del monto a enviar</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 form-group">
                                <div class="card">
                                    <div class="card-header">
                                        <i class="fas fa-terminal"></i> <b>Transferencias Realizadas</b>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-12 form-group">
                                                <table class="table table-striped table-bordered display responsive nowrap">
                                                    <thead class="bg-dark text-white">
                                                        <tr>
                                                            <th class="text-center">Banco</th>
                                                            <th class="text-center">Número de cuenta</th>
                                                            <th class="text-center">Monto Enviado</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($transfers as $transfer)
                                                            <tr>
                                                                <td class="text-left">
                                                                    <label>{{$transfer->currency_bankname}}</label>
                                                                </td>
                                                                <td class="text-left">
                                                                    <label>{{$transfer->transfer_account_number}}</label>
                                                                </td>
                                                                <td class="text-right">
                                                                    <label>{{trim($transfer->transfer_amount_fm)}}</label>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="col-md-12 form-group">
                                <div class="row">
                                    <div class="col-md-6 form-group">
                                        <label for="currencybank_id" class="form-label"><b>Transferir desde el banco destino (*):</b></label>
                                        <select id="currencybank_id" name="currencybank_id" class="form-control" onchange="getAvailable(this.value)" onclick="quitaMensaje()">
                                            <option value="">Seleccionar</option>
                                            @foreach ($currency_banks as $currency_bank)
                                                <option value="{{$currency_bank->id}}">{{$currency_bank->bankname}} {{$currency_bank->account_number}}</option>
                                            @endforeach
                                        </select>
                                        <div id="currencybank_id_error" class="talert" style='display: none;'>
                                            <p class="text-danger">El banco destino es requerido</p>
                                        </div>
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label for="available_amount" id="label_available_amount">Monto disponible en {{$transactions[0]->currency2}}({{$transactions[0]->symbol2}}):</label>
                                        <input disabled type="text" class="form-control text-right" style="color:darkgreen; background-color: white" id="available_amount" name="available_amount" value="" placeholder="Monto disponible">
                                        <input type="hidden" id="real_available_amount" name="real_available_amount" value="">
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label for="amount" id="label_amount">Transferir en {{$transactions[0]->currency2}}({{$transactions[0]->symbol2}}) (*):</label>
                                        <input type="text" class="form-control text-right" style="color:darkgreen; background-color: white" id="amount" name="amount" value="{{number_format(trim($sumamount_rest), 2, ',', '.')}}" oninput="procesarValor(this)" onkeypress="quitaMensaje()" placeholder="Monto a transferir">
                                        <input type="hidden" id="real_amount" name="real_amount" value="{{$sumamount_rest}}">
                                    </div>
                                    <div id="amount_error" class="talert" style='display: none;'>
                                        <p class="text-danger">El Monto a transferir es requerido</p>
                                    </div>
                                    <div id="amount_error2" class="talert" style='display: none;'>
                                        <p class="text-danger">El Monto a transferir no puede ser mayor que el monto disponible</p>
                                    </div>
                                    <div id="amount_error3" class="talert" style='display: none;'>
                                        <p class="text-danger">El Monto a transferir no puede ser mayor que la totalidad del monto a enviar</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header"><i class="fas fa-desktop"></i>
                                    <b> Agregar capture de la transacción bancaria hecha (*)</b>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <label class="control-label">
                                            Imágen a Mostrar:
                                        </label>
                                        <div class="panel-body text-center" id='imgfoto'>
                                            <div class="row">
                                                <div class="col-md-12 form-group">
                                                    <img width='300' height='200' alt="" id="imagen"
                                                        style="max-width: 100%; max-height: 100%;" src="/images/capture.png" />
                                                </div>
                                            </div>
                                        </div>
                                        <label class="control-label">
                                            Nombre del Archivo:
                                        </label>
                                        <input readonly class="form-control" type="text" id="linkaddress_i"
                                            name="linkaddress_i" value="" maxlength="250">
                                        <div id="transaction_error" class="talert" style='display: none;'>
                                            <p class="text-danger">Debe escoger una imagen para guardarla con la transacción</p>
                                        </div>
                                    </div>
                                    <div class='col-lg-5 form-group'>
                                        <label class="control-label">Buscar la imágen en su Celular</label>
                                        <div class='btn btn-grey btn-file'>
                                            <i class='fas fa-folder-open'></i> Seleccionar archivo
                                            <input id='fileInput' name='fileInput' type='file' accept="image/*"
                                                class='file-input' onchange='mostrarImagen()' onclick='quitaMensaje()'>
                                            <div class="col-md-4" id='imagengif' style='display: none;'>
                                                <img src="/images/loading.gif" border='0' /> Subiendo Archivo al Servidor, Espere un
                                                momento...
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="col-md-12 form-group">
                        <b>(*) Campos obligatorios</b>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-2 form-group text-center">
                            <button type="button" id="buttongrabar" onclick="grabar()" class="btn btn-primary btn-block">Grabar  <i class="fa fa-save"></i></button>
                        </div>
                        @if ($origin == 'transaction')
                            <div class="col-md-2 form-group text-center">
                                <a href="/transaction" class="btn btn-secondary btn-block">Regresar  <i class="fa fa-arrow-circle-left"></i></a>
                            </div>
                        @else
                            <div class="col-md-2 form-group text-center">
                                <a href="/proccess" class="btn btn-secondary btn-block">Regresar  <i class="fa fa-arrow-circle-left"></i></a>
                            </div>
                        @endif

                    </div>
                    <hr>
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
    function getAvailable(xid){
        fetch(`/available/${xid}`)
            .then(response => response.json())
            .then(jsondata => showAvailable(jsondata))
    }

    function showAvailable(jsondata){
        let available_amount = jsondata.available_amount;
        let real_available_amount = jsondata.real_available_amount;

        document.getElementById("available_amount").value = available_amount;
        document.getElementById("real_available_amount").value = real_available_amount;
    }

    function procesarValor(input) {
        let valorActual = input.value.replace(/\./g, '').replace(',', '.');

        // Convertimos el valor a un número flotante para realizar cálculos
        let numeroSinFormato = parseFloat(valorActual);

        document.getElementById('real_amount').value = numeroSinFormato;

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

    function mostrarImagen() {
        var input = document.getElementById('fileInput');
        var imagen = document.getElementById('imagen');
        var rutaInput = document.getElementById('linkaddress_i');

        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
                var img = new Image();
                img.src = e.target.result;

                img.onload = function() {
                    // Obtener las dimensiones de la imagen
                    var width = img.width;
                    var height = img.height;

                    // Calcular las relaciones de aspecto
                    var aspectRatioWidth = width / height;
                    var aspectRatioHeight = height / width;

                    // Determinar la orientación
                    var orientation;
                    if (Math.abs(aspectRatioWidth - aspectRatioHeight) < 0.3) {
                        orientation = 'CUA';
                    } else if (aspectRatioWidth > aspectRatioHeight) {
                        orientation = 'HOR';
                    } else {
                        orientation = 'VER';
                    }

                    // Asignar la orientación al campo oculto
                    document.getElementById("orientation").value = orientation;

                    // Asignar la imagen y los estilos según la orientación
                    imagen.src = e.target.result;

                    switch (orientation){
                        case 'VER':
                            imagen.style.width = "300px";
                            imagen.style.height = "400px";
                            break;
                        case 'CUA':
                            imagen.style.width = "250px";
                            imagen.style.height = "250px";
                            break;
                        case 'HOR':
                            imagen.style.width = "300px";
                            imagen.style.height = "200px";
                            break;
                    }

                    var nombreArchivo = input.files[0].name;
                    rutaInput.value = nombreArchivo;
                };
            };

            reader.readAsDataURL(input.files[0]);
        }
    }

    function grabar(){
        var xseguir = true;
        var xcurrencybank_id = document.getElementById("currencybank_id").value;
        if (xcurrencybank_id.length < 1){
            xseguir = false;
            document.getElementById("currencybank_id_error").style.display = "block";
        }
        var xamount = document.getElementById("amount").value;
        if (xamount.length < 1){
            xseguir = false;
            document.getElementById("amount_error").style.display = "block";
        } else {
            var xreal_amount = document.getElementById("real_amount").value;
            if (xreal_amount <= 0){
                xseguir = false;
                document.getElementById("amount_error").style.display = "block";
            } else {
                var xreal_available_amount = document.getElementById("real_available_amount").value;
                if (parseFloat(xreal_amount) > parseFloat(xreal_available_amount)){
                    xseguir = false;
                    document.getElementById("amount_error2").style.display = "block";
                } else {
                    var xreal_mount_change = document.getElementById("real_mount_change").value;
                    var xamount_rest = document.getElementById("amount_rest").value;
                    let xdefamount = xreal_mount_change - xamount_rest;
                    if (xreal_amount > xdefamount){
                        xseguir = false;
                        document.getElementById("amount_error3").style.display = "block";
                    }
                }
            }
        }
        var xlinkaddress_i = document.getElementById("linkaddress_i").value;
        if (xlinkaddress_i.length < 1){
            xseguir = false;
            document.getElementById("transaction_error").style.display = "block";
        }
        if (xseguir){
            document.view.submit();
        }
    }
</script>
@stop
