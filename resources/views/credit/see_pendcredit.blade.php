@extends('adminlte::page')

@section('title', 'Créditos pendientes')

@section('css')
    <link href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
@stop

@section('content_header')
    <h1 class="m-0 text-primary text-center"><b>Créditos pendientes de {{$credits[0]->user_name}}</b></h1>
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
    <input type="hidden" id="transaction_id" name="transaction_id" value="">
    <input type="hidden" id="toaction" name="toaction" value="det_pendcredit">

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-gift"></i><b> Lista de Créditos pendientes por cobrar</b>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 form-group">
                            <label for="total_charge"><strong>Monto total del Crédito:</strong></label>
                            <input disabled type="text" class="form-control text-right" style="color: red; background-color: white; font-weight: bold; font-size: 20px;" id="total_charge" name="total_charge" value="{{$net_amount}}">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-2 form-group text-center">
                            <a href="/pendcredit" class="btn btn-secondary btn-block">Volver  <i class="fa fa-arrow-circle-left"></i></a>
                        </div>
                    </div>
                    <hr>
                    <br>
                    <div id="datatable3" style='display: block;'>
                        <table id="tuser" class="table table-striped table-bordered display responsive nowrap">
                            <thead class="bg-dark text-white">
                                <tr>
                                    <th width="30" class="text-center">ID</th>
                                    <th class="text-center">Cliente</th>
                                    <th class="text-center">Cuenta</th>
                                    <th class="text-center">Banco</th>
                                    <th class="text-center">Fecha</th>
                                    <th width="200" class="text-center">Monto de la deuda</th>
                                    <th width="70" class="text-center">Ver</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach  ($credits as $credit)
                                    <tr>
                                        <td width="30" class="text-left">{{ $credit->transaction_id }}</td>
                                        <td class="text-left">{{ $credit->account_holder }}</td>
                                        <td class="text-left">{{ $credit->account_number }}</td>
                                        <td class="text-left">{{ $credit->bankname }}</td>
                                        <td class="text-left">{{ date('d-m-Y',strtotime($credit->date_debt)) }}</td>
                                        <td width="200" class="text-right">{{ trim($credit->net_amount_fm) }}{{$credit->symbol}} {{$credit->currency}}</td>
                                        <td width="70" class="text-center">
                                            @if ($permissions > 0)
                                                <a href="#" onclick="validar({{ $credit->transaction_id }})"
                                                    class="btn btn-success btn-sm" title="Ver detalles">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div id="datatable4" style='display: none;'>
                        <table id="tbank4">
                            <tbody>
                                @foreach ($credits2 as $credit2)
                                    <tr>
                                        <td>
                                            <div class="row">
                                                <div>
                                                    <b>ID:</b>
                                                </div>
                                                <div>
                                                    {{ $credit2->transaction_id }}
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="row">
                                                <div>
                                                    <b>Cliente:</b>
                                                </div>
                                                <div>
                                                    {{ $credit2->account_holder }}
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="row">
                                                <div>
                                                    <b>Cuenta:</b>
                                                </div>
                                                <div>
                                                    {{ $credit2->account_number }}
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="row">
                                                <div>
                                                    <b>Banco:</b>
                                                </div>
                                                <div>
                                                    {{ $credit2->bankname }}
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="row">
                                                <div>
                                                    <b>Fecha:</b>
                                                </div>
                                                <div>
                                                    <b>{{ date('d-m-Y',strtotime($credit2->date_debt)) }}</b>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="row">
                                                <div>
                                                    <b>Monto de la deuda:</b>
                                                </div>
                                                <div style="color: red">
                                                    <b>{{ trim($credit2->net_amount_fm) }}{{$credit2->symbol}} {{$credit2->currency}} </b>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            @if ($permissions > 0)
                                                <a href="#" onclick="validar({{ $credit2->transaction_id }})"
                                                    class="btn btn-success btn-sm" data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="Ver detalles">Ver detalles <i class="fa fa-eye"></i>
                                                </a>
                                            @endif
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
        $(document).ready(function() {
            if (window.innerWidth > 768){
                $('#datatable3').css('display', 'block');
                $('#datatable4').css('display', 'none');
            } else {
                $('#datatable3').css('display', 'none');
                $('#datatable4').css('display', 'block');
            }
        });
    </script>
<script>
    function validar(xid) {
        document.getElementById("transaction_id").value= xid;

        document.view.submit();
    }
</script>
@stop
