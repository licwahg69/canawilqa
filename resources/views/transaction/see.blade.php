@extends('adminlte::page')

@section('title', 'Datos de la Transacción')

@section('content_header')
    <h1 class="m-0 text-primary text-center"><b>Datos de la Transacción: <label style="color:black">{{$transactions[0]->complete_description}}</label></b></h1>
@stop

@section('content')
<form action="/resend_image" method="POST" id="view" name="view" class="formeli">
    @csrf
    <input type="hidden" id="type_screen" name="type_screen" value="">
    <input type="hidden" id="toaction" name="toaction" value="">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header"><i class="fas fa-cash-register"></i>
                    <b> Datos de la Transacción
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
                        (Estatus: <i class="fas fa-traffic-light"></i> {{$transactions[0]->sendstatus_text}})</label></b>
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
                    @if ($transactions[0]->sendstatus == 'TRA')
                        <div class="card">
                            <div class="card-header">
                                <i class="fas fa-dollar-sign"></i> <b>Datos de la transferencia</b>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-5 form-group">
                                        <label for="canawil_bank_name" class="form-label"><b>Fondos transferidos desde el banco Canawil:</b></label>
                                        <input disabled class="form-control" type="text" id="canawil_bank_name" name="canawil_bank_name" value="{{$transfers[0]->canawil_bank_name}}">
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <label for="transfer_waytopay_description" class="form-label"><b>Tipo de pago a usado:</b></label>
                                        <input disabled class="form-control" type="text" id="transfer_waytopay_description" name="transfer_waytopay_description" value="{{$transfers[0]->transfer_waytopay_description}}">
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label for="transfer_waytopay_reference" class="form-label" id="label_reference"><b>{{$transfers[0]->transfer_reference_text}}:</b></label>
                                        <input disabled class="form-control" type="text" id="transfer_waytopay_reference" name="transfer_waytopay_reference" value="{{$transfers[0]->transfer_waytopay_reference}}" placeholder="Referencia">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header"><i class="fas fa-camera"></i>
                                <b> Foto de la transferencia bancaria realizada</b>
                            </div>
                            <div class="card-body">
                                <div class="panel-body text-center" id='imgfoto'>
                                    <div class="row">
                                        <div class="col-md-12 form-group text-center" id="foto_web2" style='display: none;'>
                                            @switch($transfers[0]->transfer_image_orientation)
                                                @case('VER')
                                                    <img width='350' height='600' alt="" id="imagen" style="max-width: 100%; max-height: 100%;" src="{{$transfers[0]->transfer_bank_image}}" />
                                                    @break
                                                @case('CUA')
                                                    <img width='400' height='400' alt="" id="imagen" style="max-width: 100%; max-height: 100%;" src="{{$transfers[0]->transfer_bank_image}}" />
                                                    @break
                                                @case('HOR')
                                                    <img width='600' height='300' alt="" id="imagen" style="max-width: 100%; max-height: 100%;" src="{{$transfers[0]->transfer_bank_image}}" />
                                                    @break
                                            @endswitch
                                        </div>
                                        <div class="col-md-12 form-group text-center" id="foto_mobile2" style='display: none;'>
                                            @switch($transfers[0]->transfer_image_orientation)
                                                @case('VER')
                                                    <img width='350' height='600' alt="" id="imagen" style="max-width: 100%; max-height: 100%;" src="{{$transfers[0]->transfer_bank_image}}" />
                                                    @break
                                                @case('CUA')
                                                    <img width='400' height='400' alt="" id="imagen" style="max-width: 100%; max-height: 100%;" src="{{$transfers[0]->transfer_bank_image}}" />
                                                    @break
                                                @case('HOR')
                                                    <img width='500' height='200' alt="" id="imagen" style="max-width: 100%; max-height: 100%;" src="{{$transfers[0]->transfer_bank_image}}" />
                                                    @break
                                            @endswitch
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    <br>
                    <div class="row">
                        <div class="col-md-2 form-group text-center">
                            <a href="/daily" class="btn btn-secondary btn-block">Volver  <i class="fa fa-arrow-circle-left"></i></a>
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
            document.getElementById("foto_web").style.display = "block";
            document.getElementById("foto_mobile").style.display = "none";
            if (document.getElementById("foto_web2")){
                document.getElementById("foto_web2").style.display = "block";
                document.getElementById("foto_mobile2").style.display = "none";
            }
        } else {
            document.getElementById("foto_web").style.display = "none";
            document.getElementById("foto_mobile").style.display = "block";
            if (document.getElementById("foto_web2")){
                document.getElementById("foto_web2").style.display = "none";
                document.getElementById("foto_mobile2").style.display = "block";
            }
        }
    });
</script>
<script>
    $(document).ready(function() {
        var xcuenta = document.getElementById("canawil_account_number").value;
        if (xcuenta.length > 4) {
            // Obtener los últimos 4 dígitos
            let ultimos4 = xcuenta.slice(-4);
            // Reemplazar el resto con "x"
            let enmascarado = "X".repeat(xcuenta.length - 4) + ultimos4;
            // Mostrar el valor enmascarado en el input
            document.getElementById("canawil_account_number").value = enmascarado;
        }

        var xcuenta2 = document.getElementById("account_number").value;
        if (xcuenta2.length > 4) {
            // Obtener los últimos 4 dígitos
            let xultimos4 = xcuenta2.slice(-4);
            // Reemplazar el resto con "x"
            let enmascarado2 = "X".repeat(xcuenta2.length - 4) + xultimos4;
            // Mostrar el valor enmascarado en el input
            document.getElementById("account_number").value = enmascarado2;
        }

        var xcuenta3 = document.getElementById("transfer_waytopay_reference").value;
        if (xcuenta3.length > 4) {
            // Obtener los últimos 4 dígitos
            let xxultimos4 = xcuenta3.slice(-4);
            // Reemplazar el resto con "x"
            let enmascarado3 = "X".repeat(xcuenta3.length - 4) + xxultimos4;
            // Mostrar el valor enmascarado en el input
            document.getElementById("transfer_waytopay_reference").value = enmascarado3;
        }

        var xcuenta4 = document.getElementById("waytopay_reference").value;
        if (xcuenta4.length > 4) {
            // Obtener los últimos 4 dígitos
            let xxxultimos4 = xcuenta4.slice(-4);
            // Reemplazar el resto con "x"
            let enmascarado4 = "X".repeat(xcuenta4.length - 4) + xxxultimos4;
            // Mostrar el valor enmascarado en el input
            document.getElementById("waytopay_reference").value = enmascarado4;
        }
    });
</script>
@stop
