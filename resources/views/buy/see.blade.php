@extends('adminlte::page')

@section('title', 'Histórico de Compras')

@section('content_header')
    <h1 class="m-0 text-primary text-center"><b>Ver Compra de Divisa</b></h1>
@stop

@section('content')
<form action="/buy" method="POST" id="view" name="view">
    @csrf
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
                            <label for="countryname" class="form-label"><b>País destino de la compra:</b></label>
                            <input disabled class="form-control height" type="text" id="countryname" name="countryname" value="{{$buys[0]->countryname}}">
                        </div>
                        <div class="col-md-5 form-group">
                            <label for="bankname" class="form-label"><b>Banco destino de la compra:</b></label>
                            <input disabled class="form-control height" type="text" id="bankname" name="bankname" value="{{$buys[0]->bankname}}">
                        </div>
                        <div class="col-md-3 form-group">
                            <label for="account_number" class="form-label"><b>Número de cuenta:</b></label>
                            <input disabled class="form-control height" type="text" id="account_number" name="account_number" value="{{$buys[0]->account_number}}">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3 form-group">
                            <label for="purchased_amount" id="label_purchased_amount">Monto a comprar en {{$buys[0]->currency}}({{$buys[0]->symbol}}):</label>
                            <input disabled type="text" class="form-control text-right" id="purchased_amount" name="purchased_amount" value="{{ trim($buys[0]->purchased_amount_fm) }}">
                         </div>
                        <div class="col-md-3 form-group">
                            <label for="exchange_rate" id="label_exchange_rate">Tasa de cambio en {{$buys[0]->currency2}}({{$buys[0]->symbol2}}):</label>
                            <input disabled type="text" class="form-control text-right" style="color: red; background-color: white" id="exchange_rate" name="exchange_rate" value="{{ trim($buys[0]->exchange_rate_fm) }}">
                        </div>
                        <div class="col-md-3 form-group">
                            <label for="converted_amount" id="label_converted_amount">Monto requerido en {{$buys[0]->currency2}}({{$buys[0]->symbol2}}):</label>
                            <input disabled type="text" class="form-control text-right" style="color:darkgreen; background-color: white" id="converted_amount" name="converted_amount" value="{{ trim($buys[0]->converted_amount_fm) }}">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-2 form-group text-center">
                            <a href="/historybuy" class="btn btn-secondary btn-block">Cancelar  <i class="fa fa-arrow-circle-left"></i></a>
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
