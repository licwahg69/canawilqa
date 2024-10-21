@extends('adminlte::page')

@section('title', 'Histórico de Compras')

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
    <h1 class="m-0 text-primary text-center"><b>Histórico de Compras</b></h1>
@stop

@section('content')
<form action="/buy" method="POST" id="view" name="view" class="formeli">
    @csrf
    <input type="hidden" id="buy_id" name="buy_id" value="">
    <input type="hidden" id="toaction" name="toaction" value="">
    <div class="row">
        <div class="col-md-2 form-group text-center">
            <a href="home" class="btn btn-secondary btn-block">Volver  <i class="fa fa-arrow-circle-left"></i></a>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <br>
                    <div id="datatable1" style='display: block;'>
                        <table id="tbank" class="table table-striped table-bordered display responsive nowrap">
                            <thead class="bg-dark text-white">
                                <tr>
                                    <th width="30" class="text-center">ID</th>
                                    <th width="40" class="text-center">Fecha</th>
                                    <th class="text-center">País</th>
                                    <th class="text-center">Banco</th>
                                    <th class="text-center">Monto de la compra</th>
                                    <th width="60" class="text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($buys as $buy)
                                    <tr>
                                        <td width="30" class="text-left">{{ $buy->id }}</td>
                                        <td width="40" class="text-center">{{ \Carbon\Carbon::parse($buy->created_at)->format('d-m-Y H:i:s') }}</td>
                                        <td class="text-left">{{ $buy->countryname }}</td>
                                        <td class="text-left">{{ $buy->bankname }}</td>
                                        <td class="text-right">{{ $buy->purchased_amount_fm }}{{ $buy->symbol }} {{ $buy->currency }}</td>
                                        <td width="60" class="text-center">
                                            @if ($permissions > 0)
                                                <a href="#" onclick="validar('see',{{ $buy->id }})"
                                                    class="btn btn-xs btn-default text-success mx-1 shadow" title="Ver compra">
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
                                @foreach($buys2 as $buy2)
                                <tr>
                                    <td>
                                        <div class="row">
                                            <div>
                                                <b>ID:</b>
                                            </div>
                                            <div>
                                                {{ $buy2->id }}
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="row">
                                            <div>
                                                <b>Fecha:</b>
                                            </div>
                                            <div>
                                                {{ \Carbon\Carbon::parse($buy2->created_at)->format('d-m-Y H:i:s') }}
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="row">
                                            <div>
                                                <b>País:</b>
                                            </div>
                                            <div>
                                                {{ $buy2->countryname }}
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="row">
                                            <div>
                                                <b>Banco:</b>
                                            </div>
                                            <div>
                                                {{ $buy2->bankname }}
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="row">
                                            <div>
                                                <b>Monto de la compra:</b>
                                            </div>
                                            <div>
                                                {{ $buy2->purchased_amount_fm }}{{ $buy2->symbol }} {{ $buy2->currency }}
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if ($permissions > 0)
                                            <a href="#" onclick="validar('see',{{ $buy2->id }})"
                                                class="btn btn-success btn-sm" data-bs-toggle="tooltip" data-bs-placement="top"
                                                title="Ver compra">Ver <i class='fa fa-eye'></i>
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <br>
                        {{ $buys2->links() }}
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
                        [1, "desc"],
                        [2, "asc"],
                        [3, "asc"]
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
    function validar(xaccion, xid) {
        document.getElementById('toaction').value = xaccion;
        document.getElementById('buy_id').value = xid;

        document.view.submit();
    }
</script>
@stop
