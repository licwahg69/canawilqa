@extends('adminlte::page')

@section('title', 'Datos de la Transacción')

@section('content_header')
    <h1 class="m-0 text-primary text-center"><b>Datos de la Transacción: <label style="color:black">{{$transactions[0]->complete_description}}</label></b></h1>
@stop

@section('content')
<style type="text/css">
    .containerimg2 {
        width: 600px;
        height: 300px;
        margin-left: 200px;
    }
    .containerimg2 img {
        width: 100%;
        height: 100%;
    }
    .containerimgver {
        width: 350px;
        height: 600px;
        margin-left: 300px;
    }
    .containerimgver img {
        width: 100%;
        height: 100%;
    }
    .containerimgcua {
        width: 400px;
        height: 400px;
        margin-left: 300px;
    }
    .containerimgcua img {
        width: 100%;
        height: 100%;
    }

    @media (max-width: 768px) {
        .containerimg2 {
            width: 250px;
            height: 200px;
            margin-left: 25px;
        }
        .containerimgver {
            width: 220px;
            height: 350px;
            margin-left: 40px;
        }
        .containerimgcua {
            width: 250px;
            height: 250px;
            margin-left: 25px;
        }
    }
</style>
<form action="/history" method="POST" id="view" name="view" class="formeli">
    @csrf
    <input type="hidden" id="report" name="report" value="{{$report}}">
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
                        <div class="col-md-12 form-group">
                            <label for="comercial_name" class="form-label"><b>Cliente:</b></label>
                            <input disabled class="form-control" type="text" id="comercial_name" name="comercial_name" value="{{$transactions[0]->comercial_name}}">
                        </div>
                    </div>
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
                            <input disabled type="text" class="form-control text-right" style="color: red; background-color: white" id="conversion_value" name="conversion_value" value="{{$transactions[0]->two_decimals == 'Y' ? number_format($transactions[0]->conversion_value,2,',','.').' '.$transactions[0]->currency : $transactions[0]->conversion_value.' '.$transactions[0]->currency}}">
                        </div>
                        <div class="col-md-3 form-group">
                            <label for="mount_change" id="label_mount_change">Monto a pagar {{$transactions[0]->currency2}}:</label>
                            <input disabled type="text" class="form-control text-right" style="color:darkgreen; background-color: white" id="mount_change" name="mount_change" value="{{ trim($transactions[0]->mount_change_fm).' '.$transactions[0]->symbol2 }}">
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
                        <hr>
                        <h5 style="color: blue"><b>Datos de la transferencia del Aliado/Usuario</b></h5>
                        <hr>
                        <br>
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
                                <div id="datatable3" style='display: block;'>
                                    <div class="row">
                                        <div class="col-md-12 form-group">
                                            <table class="table table-striped table-bordered display responsive nowrap">
                                                <thead class="bg-dark text-white">
                                                    <tr>
                                                        <th class="text-center">Banco</th>
                                                        <th class="text-center">Monto</th>
                                                        <th class="text-center">Fecha</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($transfers as $transfer)
                                                        <tr>
                                                            <td class="text-left">
                                                                <label>{{$transfer->currency_bankname}}</label>
                                                            </td>
                                                            <td class="text-right">
                                                                <label>{{$transfer->transfer_amount_fm}}{{$transfer->symbol2}} {{$transfer->currency2}}</label>
                                                            </td>
                                                            <td class="text-center">
                                                                <label>{{\Carbon\Carbon::parse($transfer->transfer_date)->format('d-m-Y')}}</label>
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
                        <div class="card">
                            <div class="card-header"><i class="fas fa-camera"></i>
                                <b> Foto(s) de la transferencia bancaria realizada</b>
                            </div>
                            <div class="card-body">
                                <div class="panel-body text-center" id='imgfoto'>
                                    <div class="row">
                                        <div class="col-md-12 form-group">
                                            <div id="carousel1" class="carousel slide" data-bs-ride="carousel" data-bs-interval="false" style="background-color: #b4b4b0; border-style:inset">
                                                <div class="carousel-indicators">
                                                    @php
                                                        $switche=0;
                                                        $indicator=0;
                                                    @endphp
                                                    @foreach ($transfers as $transfer2)
                                                        @if ($switche==0)
                                                            <button type="button" data-bs-target="#carousel1" data-bs-slide-to="{{$indicator}}" class="active" aria-current="true" aria-label="Slide {{$transfer2->transfer_bank_image}}"></button>
                                                            @php
                                                                $indicator++;
                                                                $switche=1;
                                                            @endphp
                                                        @else
                                                            <button type="button" data-bs-target="#carousel1" data-bs-slide-to="{{$indicator}}" aria-label="Slide {{$transfer2->transfer_bank_image}}"></button>
                                                            @php
                                                                $indicator++;
                                                            @endphp
                                                        @endif
                                                    @endforeach
                                                </div>
                                                <div class="carousel-inner">
                                                    @php
                                                        $switche=0;
                                                    @endphp
                                                    @foreach ($transfers as $transfer2)
                                                        @if ($switche==0)
                                                            @php
                                                                $switche=1;
                                                            @endphp
                                                            @switch($transfer2->transfer_image_orientation)
                                                                @case("HOR")
                                                                    <div class="containerimg2 carousel-item active">
                                                                        <img src="{{$transfer2->transfer_bank_image}}" class="d-block w-100" alt="Imagen...">
                                                                    </div>
                                                                    @break
                                                                @case("VER")
                                                                    <div class="containerimgver carousel-item active">
                                                                        <img src="{{$transfer2->transfer_bank_image}}" class="d-block w-100" alt="Imagen...">
                                                                    </div>
                                                                    @break
                                                                @case("CUA")
                                                                    <div class="containerimgcua carousel-item active">
                                                                        <img src="{{$transfer2->transfer_bank_image}}" class="d-block w-100" alt="Imagen...">
                                                                    </div>
                                                                    @break
                                                            @endswitch
                                                        @else
                                                            @switch($transfer2->transfer_image_orientation)
                                                                @case("HOR")
                                                                    <div class="containerimg2 carousel-item">
                                                                        <img src="{{$transfer2->transfer_bank_image}}" class="d-block w-100" alt="Imagen...">
                                                                    </div>
                                                                    @break
                                                                @case("VER")
                                                                    <div class="containerimgver carousel-item">
                                                                        <img src="{{$transfer2->transfer_bank_image}}" class="d-block w-100" alt="Imagen...">
                                                                    </div>
                                                                    @break
                                                                @case("CUA")
                                                                    <div class="containerimgcua carousel-item">
                                                                        <img src="{{$transfer2->transfer_bank_image}}" class="d-block w-100" alt="Imagen...">
                                                                    </div>
                                                                    @break
                                                            @endswitch
                                                        @endif
                                                    @endforeach
                                                </div>
                                                <button class="carousel-control-prev" type="button" data-bs-target="#carousel1" data-bs-slide="prev">
                                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                                    <span class="visually-hidden"></span>
                                                </button>
                                                <button class="carousel-control-next" type="button" data-bs-target="#carousel1" data-bs-slide="next">
                                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                                    <span class="visually-hidden"></span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    <br>
                    <div class="row">
                        <div class="col-md-2 form-group text-center">
                            <a href="/ret_admhistory/{{$desde}}/{{$hasta}}/{{$report}}/{{$user_id}}" class="btn btn-secondary btn-block">Volver  <i class="fa fa-arrow-circle-left"></i></a>
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
@stop
