@extends('adminlte::page')

@section('title', 'Detalles del Cobro')

@section('css')
    <link href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
    <style>
        @media (max-width: 768px) {
            /* Estilos para dispositivos móviles */
            #tuser2 {
                width: 100%;
                font-size: 12px; /* Reducir el tamaño de fuente para una mejor legibilidad */
            }

            #tuser2 tr {
                display: flex;
                flex-direction: column;
                border: solid 1px gray;
                padding: 1em;
            }
        }
    </style>
@stop

@section('content_header')
    <h1 class="m-0 text-primary text-center"><b>Detalles del Cobro</b></h1>
@stop

@section('content')
    <form action="/transaction" method="POST" id="view" name="view" class="formeli">
        @csrf
        <input type="hidden" id="wholesaler_id" name="wholesaler_id" value="">
        <input type="hidden" id="toaction" name="toaction" value="">
        <div class="row">
            <div class="col-md-2 form-group text-center">
                <a href="/wholesaler_report" class="btn btn-secondary btn-block">Volver <i class="fa fa-arrow-circle-left"></i></a>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <br>
                        <div id="datatable1" style='display: block;'>
                            <table id="tuser" class="table table-striped table-bordered display responsive nowrap">
                                <thead class="bg-dark text-white">
                                    <tr>
                                        <th class="text-center">Afiliado</th>
                                        <th width="90" class="text-center">Fecha</th>
                                        <th class="text-center">Monto Original</th>
                                        <th class="text-center">Ganancia Canawil</th>
                                        <th class="text-center">Comisión</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($wholesaler_payment_details as $wholesaler_payment_detail)
                                        <tr>
                                            <td class="text-left">{{ $wholesaler_payment_detail->affilieate_comercial_name }}</td>
                                            <td width="90" class="text-center">{{ date('d-m-Y',strtotime($wholesaler_payment_detail->send_date)) }}</td>
                                            <td class="text-right">{{ $wholesaler_payment_detail->mount_value_fm }} {{$wholesaler_payment_detail->currency}}</td>
                                            <td class="text-right">{{ $wholesaler_payment_detail->canawil_amount_fm }} {{$wholesaler_payment_detail->currency}}</td>
                                            <td class="text-right">{{ $wholesaler_payment_detail->wholesaler_amount_fm }} {{$wholesaler_payment_detail->currency}}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div id="datatable2" style='display: none;'>
                            <table id="tuser2">
                                <tbody>
                                    @foreach($wholesaler_payment_details2 as $wholesaler_payment_detail2)
                                    <tr>
                                        <td>
                                            <div class="row">
                                                <div>
                                                    <b>Afiliado:</b>
                                                </div>
                                                <div>
                                                    {{ $wholesaler_payment_detail2->affilieate_comercial_name }}
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="row">
                                                <div>
                                                    <b>Fecha:</b>
                                                </div>
                                                <div>
                                                    {{ date('d-m-Y',strtotime($wholesaler_payment_detail2->send_date)) }}
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="row">
                                                <div>
                                                    <b>Monto Original:</b>
                                                </div>
                                                <div>
                                                    {{ $wholesaler_payment_detail2->mount_value_fm }} {{$wholesaler_payment_detail2->currency }}
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="row">
                                                <div>
                                                    <b>Ganancia Canawil:</b>
                                                </div>
                                                <div>
                                                    {{ $wholesaler_payment_detail2->canawil_amount_fm }} {{$wholesaler_payment_detail2->currency }}
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="row">
                                                <div>
                                                    <b>Comisión:</b>
                                                </div>
                                                <div>
                                                    {{ $wholesaler_payment_detail2->wholesaler_amount_fm }} {{$wholesaler_payment_detail2->currency }}
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <br>
                            {{ $wholesaler_payment_details2->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <input type="hidden" id="nombrelimina" name="nombrelimina" value="">
    </form>
    <form action="/force-logout" id="forcelogout" name="forcelogout" method="post">
        @csrf
        <input type="hidden" id="href" name="href" value="">
    </form>
@stop

@section('footer')
    <div class="float-right d-sm-inline">
        <label class="text-primary">© {{ date_format(date_create(date('Y')), 'Y') }} Cambios CANAWIL</label>, todos los derechos
        reservados.
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

                $('#tuser').DataTable({
                    "lengthMenu": [
                        [5, 10, 25, -1],
                        [5, 10, 25, "Todos"]
                    ],
                    "order": [
                        [1, "asc"]
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
@stop
