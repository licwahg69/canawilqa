@extends('adminlte::page')

@section('title', 'Créditos pagados')

@section('css')
    <link href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
    <style>
        @media (max-width: 768px) {
            /* Estilos para dispositivos móviles */
            #tbank2 {
                width: 100%;
                font-size: 12px; /* Reducir el tamaño de fuente para una mejor legibilidad */
            }

            #tbank2 tr {
                display: flex;
                flex-direction: column;
                border: solid 1px gray;
                padding: 1em;
            }
        }
    </style>
@stop

@section('content_header')
    <h1 class="m-0 text-primary text-center"><b>Transacciones a Crédito cobradas</b></h1>
@stop

@section('content')
<form action="/credit" method="POST" id="view" name="view" class="formeli">
    @csrf
    <input type="hidden" id="pay_id" name="pay_id" value="">
    <input type="hidden" id="toaction" name="toaction" value="admseepay">
    <div class="row">
        <div class="col-md-2 form-group text-center">
            <a href="/paycredit" class="btn btn-secondary btn-block">Volver  <i class="fa fa-arrow-circle-left"></i></a>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 form-group">
                            <label for="comercial_name" class="form-label"><b>Cliente:</b></label>
                            <input disabled type="text" class="form-control" id="comercial_name" name="comercial_name" value="{{ $pays[0]->comercial_name }}">
                        </div>
                    </div>
                    <br>
                    <div id="datatable1" style='display: block;'>
                        <table id="tbank" class="table table-striped table-bordered display responsive nowrap">
                            <thead class="bg-dark text-white">
                                <tr>
                                    <th width="30" class="text-center">ID</th>
                                    <th class="text-center">Fecha del pago</th>
                                    <th class="text-center">Concepto</th>
                                    <th width="40" class="text-center">Monto</th>
                                    <th width="60" class="text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pays as $pay)
                                    <tr>
                                        <td width="30" class="text-left">{{ $pay->id }}</td>
                                        <td class="text-left">{{ date('d-m-Y',strtotime($pay->pay_date)) }}</td>
                                        <td class="text-left">{{ $pay->concept }}</td>
                                        <td width="40" class="text-right">{{ trim($pay->pay_amount_fm) }}</td>
                                        <td width="60" class="text-center">
                                            @if ($permissions > 0)
                                                <a href="#" onclick="validar({{ $pay->id }})"
                                                    class="btn btn-xs btn-default text-success mx-1 shadow" title="Ver {{ $pay->concept }}">
                                                    <i class="fa fa-lg fa-fw fa-eye"></i>
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div id="datatable2" style='display: none;'>
                        <table id="tbank2">
                            <tbody>
                                @foreach($pays2 as $pay2)
                                <tr>
                                    <td>
                                        <div class="row">
                                            <div>
                                                <b>ID:</b>
                                            </div>
                                            <div>
                                                {{ $pay2->id }}
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="row">
                                            <div>
                                                <b>Fecha del pago:</b>
                                            </div>
                                            <div>
                                                {{ date('d-m-Y',strtotime($pay2->pay_date)) }}
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="row">
                                            <div>
                                                <b>Concepto:</b>
                                            </div>
                                            <div>
                                                {{ $pay2->concept }}
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="row">
                                            <div>
                                                <b>Monto:</b>
                                            </div>
                                            <div>
                                                {{ trim($pay2->pay_amount_fm) }}
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if ($permissions > 0)
                                            <a href="#" onclick="validar({{ $pay2->id }})"
                                                class="btn btn-success btn-sm" data-bs-toggle="tooltip" data-bs-placement="top"
                                                title="Ver {{ $pay2->concept }}">Ver <i class='fa fa-eye'></i>
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <br>
                        {{ $pays2->links() }}
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
            if (window.innerWidth > 768){
                $('#datatable1').css('display', 'block');
                $('#datatable2').css('display', 'none');

                $('#tbank').DataTable({
                    "lengthMenu": [
                        [5, 10, 25, -1],
                        [5, 10, 25, "Todos"]
                    ],
                    "order": [
                        [0, "desc"]
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
            } else {
                $('#datatable1').css('display', 'none');
                $('#datatable2').css('display', 'block');
            }
        });
    </script>
<script>
    function validar(xid) {
        document.getElementById('pay_id').value = xid;

        document.view.submit();
    }
</script>
@stop
