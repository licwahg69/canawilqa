@extends('adminlte::page')

@section('title', 'Ganancias')

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
@stop

@section('content_header')
    <h1 class="m-0 text-primary text-center"><b>Ganancias desde el {{date('d-m-Y',strtotime($desde))}} hasta el {{date('d-m-Y',strtotime($hasta))}}</b></h1>
@stop

@section('content')
<form action="/profit" method="POST" id="view" name="view" class="formeli">
    @csrf

    <div class="row">
        <div class="col-md-2 form-group text-center">
            <a href="/profit" class="btn btn-secondary btn-block">Volver  <i class="fa fa-arrow-circle-left"></i></a>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div id="datatable3" style='display: none;'>
                        <div class="row">
                            <div class="col-md-12 form-group">
                                <table class="table table-striped table-bordered display responsive nowrap">
                                    <thead class="bg-dark text-white">
                                        <tr>
                                            <th class="text-center">Tipo de Cambio</th>
                                            <th class="text-center">Total General recibido</th>
                                            <th class="text-center">Total de ganancias</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($datos2 as $dato2)
                                            <tr>
                                                <td class="text-left">
                                                    <label style="font-size:20px">{{$dato2['a_to_b']}}</label>
                                                </td>
                                                <td class="text-right">
                                                    <label style="color:darkblue; font-size:20px">{{$dato2['general_mount_value']}}</label>
                                                </td>
                                                <td class="text-right">
                                                    <label style="color: red; font-size:20px">{{$dato2['general_canawil_amount_withheld']}}</label>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div id="datatable4" style='display: none;'>
                        <br>
                        <table id="tbank4">
                            <tbody>
                                @foreach ($datos2 as $dato2)
                                    <tr>
                                        <td>
                                            <div class="row">
                                                <div>
                                                    <b>Tipo de Cambio:</b>
                                                </div>
                                                <div>
                                                    {{ $dato2['a_to_b'] }}
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="row">
                                                <div>
                                                    <b>Total General recibido:</b>
                                                </div>
                                                <div style="color: blue">
                                                    <b>{{ $dato2['general_mount_value'] }}</b>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="row">
                                                <div>
                                                    <b>Total de ganancias:</b>
                                                </div>
                                                <div style="color: red">
                                                    <b>{{ $dato2['general_canawil_amount_withheld'] }}</b>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <br>
                    <div id="datatable1" style='display: none;'>
                        <table id="tbank" class="table table-striped table-bordered">
                            <thead class="bg-dark text-white">
                                <tr>
                                    <th class="text-center">ID</th>
                                    <th width="120" class="text-center">Fecha</th>
                                    <th class="text-center">Aliado/Usuario</th>
                                    <th width="120" class="text-center">Monto Original</th>
                                    <th class="text-center">Tasa</th>
                                    <th width="90" class="text-center">Monto Transferido</th>
                                    <th width="120" class="text-center">Monto Neto</th>
                                    <th class="text-center">Tasa de compra</th>
                                    <th width="120" class="text-center">Comisión Cliente</th>
                                    <th width="120" class="text-center">Comisión Canawil</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($transfers as $transfer)
                                    <tr>
                                        <td class="text-left">{{ $transfer->id }}</td>
                                        <td width="120" class="text-left">{{ date('d-m-Y',strtotime($transfer->transfer_date)) }}</td>
                                        <td class="text-left">{{ $transfer->comercial_name }}</td>
                                        <td width="120" class="text-right">{{ trim($transfer->mount_value_fm) }} {{$transfer->currency}}</td>
                                        <td class="text-right">{{ $transfer-> two_decimals == 'Y' ? number_format($transfer->conversion_value,2,',','.') : $transfer->conversion_value }}</td>
                                        <td width="90" class="text-right">{{ trim($transfer->mount_change_fm) }} {{$transfer->currency2}}</td>
                                        <td width="120" class="text-right">{{ trim($transfer->gross_amount_fm) }} {{$transfer->currency}}</td>
                                        <td class="text-right">{{ $transfer-> two_decimals == 'Y' ? number_format($transfer->exchange_rate,2,',','.') : $transfer->exchange_rate }}</td>
                                        <td width="120" class="text-right" style="color: red">- {{ trim($transfer->amount_withheld_fm) }} {{$transfer->currency}}</td>
                                        <td width="120" class="text-right">{{ trim($transfer->canawil_amount_withheld_fm) }} {{$transfer->currency}}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div id="datatable2" style='display: none;'>
                        <table id="tbank2">
                            <tbody>
                                @foreach($transfers2 as $transfer2)
                                <tr>
                                    <td>
                                        <div class="row">
                                            <div>
                                                <b>ID:</b>
                                            </div>
                                            <div>
                                                {{ $transfer2->id }}
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="row">
                                            <div>
                                                <b>Fecha:</b>
                                            </div>
                                            <div>
                                                {{ date('d-m-Y',strtotime($transfer2->transfer_date)) }}
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="row">
                                            <div>
                                                <b>Aliado/Usuario:</b>
                                            </div>
                                            <div>
                                                {{ $transfer2->comercial_name }}
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="row">
                                            <div>
                                                <b>Monto Original:</b>
                                            </div>
                                            <div>
                                                {{ trim($transfer2->mount_value_fm) }} {{$transfer2->currency}}
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="row">
                                            <div>
                                                <b>Tasa:</b>
                                            </div>
                                            <div>
                                                {{ $transfer2-> two_decimals == 'Y' ? number_format($transfer2->conversion_value,2,',','.') : $transfer2->conversion_value }} {{$transfer2->currency}}</td>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="row">
                                            <div>
                                                <b>Monto Transferido:</b>
                                            </div>
                                            <div>
                                                {{ $transfer2->mount_change_fm }} {{$transfer2->currency2}}
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="row">
                                            <div>
                                                <b>Monto Neto:</b>
                                            </div>
                                            <div>
                                                {{ $transfer2->gross_amount_fm }} {{$transfer2->currency}}
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="row">
                                            <div>
                                                <b>Tasa de compra:</b>
                                            </div>
                                            <div>
                                                {{ $transfer2-> two_decimals == 'Y' ? number_format($transfer2->exchange_rate,2,',','.') : $transfer2->exchange_rate }} {{$transfer2->currency}}</td>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="row">
                                            <div>
                                                <b>Comisión Cliente:</b>
                                            </div>
                                            <div style="color: red">
                                                - {{ $transfer2->amount_withheld_fm }} {{$transfer2->currency}}
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="row">
                                            <div>
                                                <b>Comisión Canawil:</b>
                                            </div>
                                            <div>
                                                {{ $transfer2->canawil_amount_withheld_fm }} {{$transfer2->currency}}
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <br>
                        {{ $transfers2->links() }}
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
                $('#datatable3').css('display', 'block');
                $('#datatable4').css('display', 'none');

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
                $('#datatable3').css('display', 'none');
                $('#datatable4').css('display', 'block');
            }
        });
    </script>
@stop
