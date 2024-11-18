@extends('adminlte::page')

@section('title', 'Créditos pagados')

@section('css')
    <link href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
@stop

@section('content_header')
    <h1 class="m-0 text-primary text-center"><b>Detalles del Pago hecho</b></h1>
@stop

@section('content')
<style>
    @media (max-width: 768px) {
        /* Estilos para dispositivos móviles */
        #tbank4 {
            width: 100%;
            font-size: 12px; /* Reducir el tamaño de fuente para una mejor legibilidad */
            background-color: rgb(197, 241, 238)
        }

        #tbank4 tr {
            display: flex;
            flex-direction: column;
            border: solid 1px gray;
            padding: 1em;
        }
    }
</style>
<form action="/credit" method="POST" id="view" name="view" class="formeli">
    @csrf
    <div class="row">
        <div class="col-12">
            <div class="row">
                <div class="col-md-2 form-group text-center">
                    <a href="/paycredit" class="btn btn-secondary btn-block">Volver  <i class="fa fa-arrow-circle-left"></i></a>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-list"></i><b> Transacciones a Crédito cobradas</b>
                </div>
                <div class="card-body">
                    <div id="datatable3" style='display: block;'>
                        <table id="tuser" class="table table-striped table-bordered display responsive nowrap">
                            <thead class="bg-dark text-white">
                                <tr>
                                    <th width="30" class="text-center">ID</th>
                                    <th class="text-center">Cliente</th>
                                    <th class="text-center">Cuenta</th>
                                    <th class="text-center">Banco</th>
                                    <th class="text-center">Fecha</th>
                                    <th width="200" class="text-center">Monto pagado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach  ($pay_details as $pay_detail)
                                    <tr>
                                        <td width="30" class="text-left">{{ $pay_detail->transaction_id }}</td>
                                        <td class="text-left">{{ $pay_detail->account_holder }}</td>
                                        <td class="text-left">{{ $pay_detail->account_number }}</td>
                                        <td class="text-left">{{ $pay_detail->bankname }}</td>
                                        <td class="text-left">{{ date('d-m-Y',strtotime($pay_detail->date_debt)) }}</td>
                                        <td width="200" class="text-right">{{ trim($pay_detail->total_debt_fm) }}{{$pay_detail->symbol}} {{$pay_detail->currency}}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div id="datatable4" style='display: none;'>
                        <table id="tbank4">
                            <tbody>
                                @foreach ($pay_details2 as $pay_detail2)
                                    <tr>
                                        <td>
                                            <div class="row">
                                                <div>
                                                    <b>ID:</b>
                                                </div>
                                                <div>
                                                    {{ $pay_detail2->transaction_id }}
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="row">
                                                <div>
                                                    <b>Cliente:</b>
                                                </div>
                                                <div>
                                                    {{ $pay_detail2->account_holder }}
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="row">
                                                <div>
                                                    <b>Cuenta:</b>
                                                </div>
                                                <div>
                                                    {{ $pay_detail2->account_number }}
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="row">
                                                <div>
                                                    <b>Banco:</b>
                                                </div>
                                                <div>
                                                    {{ $pay_detail2->bankname }}
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="row">
                                                <div>
                                                    <b>Fecha:</b>
                                                </div>
                                                <div>
                                                    {{ date('d-m-Y',strtotime($pay_detail2->date_debt)) }}
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="row">
                                                <div>
                                                    <b>Monto pagado:</b>
                                                </div>
                                                <div>
                                                    <b>{{ trim($pay_detail2->total_debt_fm) }}{{$pay_detail2->symbol}} {{$pay_detail2->currency}} </b>
                                                </div>
                                            </div>
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
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-money-check-alt"></i><b> Detalles del Pago</b>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label for="comercial_name" class="form-label"><b>Aliado Comercial/Usuario:</b></label>
                            <input disabled type="text" class="form-control" id="comercial_name" name="comercial_name" value="{{ $pays[0]->comercial_name }}">
                        </div>
                        <div class="col-md-3 form-group">
                            <label for="total_charge"><strong>Monto total del pago:</strong></label>
                            <input disabled type="text" class="form-control text-right" style="color: blue; background-color: white; font-weight: bold; font-size: 20px;" id="total_charge" name="total_charge" value="{{$pays[0]->pay_amount_fm}}">
                        </div>
                        <div class="col-md-3 form-group">
                            <label for="total_charge"><strong>Fecha del pago:</strong></label>
                            <input disabled type="text" class="form-control" id="pay_date" name="pay_date" value="{{ date('d-m-Y',strtotime($pays[0]->pay_date)) }}">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label for="bank_name" class="form-label"><b>Banco de Canawil:</b></label>
                            <input disabled type="text" class="form-control" id="bank_name" name="bank_name" value="{{ $pays[0]->bank_name }}">
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="canawil_account_number" class="form-label"><b>Cuenta:</b></label>
                            <input disabled class="form-control" type="text" id="canawil_account_number" name="canawil_account_number" value="{{ $pays[0]->account_number }}" placeholder="Cuenta">
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="send_way" class="form-label"><b>Medio para el envío:</b></label>
                            <input disabled class="form-control" type="text" id="send_way" name="send_way" value="{{ $pays[0]->description }}" placeholder="Medio para el envío">
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header"><i class="fas fa-camera"></i>
                            <b> Foto del pago bancario realizado</b>
                        </div>
                        <div class="card-body">
                            <div class="panel-body text-center" id='imgfoto'>
                                <div class="row">
                                    <div class="col-md-12 form-group text-center" id="foto_web" style='display: none;'>
                                        @switch($pays[0]->orientation)
                                            @case('VER')
                                                <img width='350' height='600' alt="" id="imagen" style="max-width: 100%; max-height: 100%;" src="{{$pays[0]->bank_image}}" />
                                                @break
                                            @case('CUA')
                                                <img width='400' height='400' alt="" id="imagen" style="max-width: 100%; max-height: 100%;" src="{{$pays[0]->bank_image}}" />
                                                @break
                                            @case('HOR')
                                                <img width='600' height='300' alt="" id="imagen" style="max-width: 100%; max-height: 100%;" src="{{$pays[0]->bank_image}}" />
                                                @break
                                        @endswitch
                                    </div>
                                    <div class="col-md-12 form-group text-center" id="foto_mobile" style='display: none;'>
                                        @switch($pays[0]->orientation)
                                            @case('VER')
                                                <img width='350' height='600' alt="" id="imagen" style="max-width: 100%; max-height: 100%;" src="{{$pays[0]->bank_image}}" />
                                                @break
                                            @case('CUA')
                                                <img width='400' height='400' alt="" id="imagen" style="max-width: 100%; max-height: 100%;" src="{{$pays[0]->bank_image}}" />
                                                @break
                                            @case('HOR')
                                                <img width='500' height='200' alt="" id="imagen" style="max-width: 100%; max-height: 100%;" src="{{$pays[0]->bank_image}}" />
                                                @break
                                        @endswitch
                                    </div>
                                </div>
                            </div>
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
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#tuser').DataTable({
                "lengthMenu": [
                    [5, 10, 25, -1],
                    [5, 10, 25, "Todos"]
                ],
                "order": [
                    [0, "asc"]
                ],
                "language": {
                    "lengthMenu": "Mostrar _MENU_ registros por página",
                    "zeroRecords": "No se encontraron resultados",
                    "info": "Mostrando página _PAGE_ de _PAGES_",
                    "infoEmpty": "No hay registros disponibles",
                    "infoFiltered": "(filtrado de _MAX_ registros totales)",
                    "search": "Buscar:",
                    "paginate": {
                        "first": "Primero",
                        "last": "Último",
                        "next": "Siguiente",
                        "previous": "Anterior"
                    }
                }
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (window.innerWidth > 768) {
                $('#datatable3').css('display', 'block');
                $('#datatable4').css('display', 'none');
                document.getElementById("foto_web").style.display = "block";
                document.getElementById("foto_mobile").style.display = "none";
            } else {
                $('#datatable3').css('display', 'none');
                $('#datatable4').css('display', 'block');
                document.getElementById("foto_web").style.display = "none";
                document.getElementById("foto_mobile").style.display = "block";
            }
        });
    </script>
@stop
