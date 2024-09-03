@extends('adminlte::page')

@section('title', 'Histórico de transacciones')

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
    <h1 class="m-0 text-primary text-center"><b>Histórico de transacciones desde el {{date('d-m-Y',strtotime($desde))}} hasta el {{date('d-m-Y',strtotime($hasta))}}</b></h1>
@stop

@section('content')
<form action="/history" method="POST" id="view" name="view" class="formeli">
    @csrf
    <input type="hidden" id="transaction_id" name="transaction_id" value="">
    <input type="hidden" id="type_screen" name="type_screen" value="">
    <input type="hidden" id="desde" name="desde" value="{{$desde}}">
    <input type="hidden" id="hasta" name="hasta" value="{{$hasta}}">
    <input type="hidden" id="toaction" name="toaction" value="">
    <div class="row">
        <div class="col-md-2 form-group text-center">
            <a href="/history" class="btn btn-secondary btn-block">Volver  <i class="fa fa-arrow-circle-left"></i></a>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div id="datatable3" style='display: block;'>
                        <div class="row">
                            <div class="col-md-12 form-group">
                                <table class="table table-striped table-bordered display responsive nowrap">
                                    <thead class="bg-dark text-white">
                                        <tr>
                                            <th class="text-center">Tipo de Cambio</th>
                                            <th class="text-center">Total General enviado en el período</th>
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
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div id="datatable4" style='display: none;'>
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
                                                    <b>Total General enviado:</b>
                                                </div>
                                                <div style="color: blue">
                                                    <b>{{ $dato2['general_mount_value'] }}</b>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <br>
                    <div id="datatable1" style='display: block;'>
                        <table id="tbank" class="table table-striped table-bordered">
                            <thead class="bg-dark text-white">
                                <tr>
                                    <th class="text-center">ID</th>
                                    <th width="60" class="text-center">Fecha</th>
                                    <th class="text-center">Conversión</th>
                                    <th class="text-center">Descripción</th>
                                    <th class="text-center">Pagador</th>
                                    <th width="100" class="text-center">Monto a Cambiar</th>
                                    <th width="100" class="text-center">Monto a Pagar</th>
                                    <th width="70" class="text-center">Estatus</th>
                                    <th width="30" class="text-center">Ver</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($transactions as $transaction)
                                    <tr>
                                        <td class="text-left">{{ $transaction->id }}</td>
                                        <td width="60" class="text-left">{{ date('d-m-Y',strtotime($transaction->send_date)) }}</td>
                                        <td class="text-left">{{ $transaction->a_to_b }}</td>
                                        <td class="text-left">{{ $transaction->complete_description }}</td>
                                        <td class="text-left">{{ $transaction->payer_name }}</td>
                                        <input type="hidden" id="nombre{{ $transaction->id }}"
                                            value="{{ $transaction->complete_description }}">
                                        <td width="100" class="text-right">{{ trim($transaction->mount_value_fm) }} {{$transaction->currency}}</td>
                                        <td width="100" class="text-right">{{ trim($transaction->mount_change_fm) }} {{$transaction->currency2}}</td>
                                        @switch($transaction->sendstatus)
                                            @case('ENV')
                                                <td width="70" class="text-center text-primary">
                                                @break
                                            @case('REC')
                                                <td width="70" class="text-center text-orange">
                                                @break
                                            @case('PRO')
                                                <td width="70" class="text-center text-cyan">
                                                @break
                                            @case('TRA')
                                                <td width="70" class="text-center text-danger">
                                                @break
                                        @endswitch
                                        <i class="fas fa-traffic-light"></i> {{ $transaction->sendstatus_text }}</td>
                                        <td width="30" class="text-center">
                                            @if ($permissions > 0)
                                                <a href="#" onclick="validar('see',{{ $transaction->id }})"
                                                    class="btn btn-xs btn-default text-success mx-1 shadow" title="Ver detalles de {{ $transaction->complete_description }}">
                                                    <i class="fas fa-lg fa-fw fa-eye"></i>
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
                                @foreach($transactions2 as $transaction2)
                                <tr>
                                    <td>
                                        <div class="row">
                                            <div>
                                                <b>ID:</b>
                                            </div>
                                            <div>
                                                {{ $transaction2->id }}
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="row">
                                            <div>
                                                <b>Fecha:</b>
                                            </div>
                                            <div>
                                                {{ date('d-m-Y',strtotime($transaction2->send_date)) }}
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="row">
                                            <div>
                                                <b>Conversión:</b>
                                            </div>
                                            <div>
                                                {{ $transaction2->a_to_b }}
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="row">
                                            <div>
                                                <b>Descripción:</b>
                                            </div>
                                            <div>
                                                {{ $transaction2->complete_description }}
                                            </div>
                                        </div>
                                    </td>
                                    <input type="hidden" id="nombre{{ $transaction2->id }}"
                                                value="{{ $transaction2->complete_description }}">
                                    <td>
                                        <div class="row">
                                            <div>
                                                <b>Pagador:</b>
                                            </div>
                                            <div>
                                                {{ $transaction2->payer_name }}
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="row">
                                            <div>
                                                <b>Monto a Cambiar:</b>
                                            </div>
                                            <div>
                                                {{ $transaction2->mount_value_fm }} {{$transaction2->currency}}
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="row">
                                            <div>
                                                <b>Monto a Pagar:</b>
                                            </div>
                                            <div>
                                                {{ $transaction2->mount_change_fm }} {{$transaction2->currency2}}
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="row">
                                            <div>
                                                <b>Estatus:</b>
                                            </div>
                                                @switch($transaction2->sendstatus)
                                                    @case('ENV')
                                                        <div class="text-primary">
                                                        @break
                                                    @case('REC')
                                                        <div class="text-orange">
                                                        @break
                                                    @case('PRO')
                                                        <div class="text-cyan">
                                                        @break
                                                    @case('TRA')
                                                        <div class="text-danger">
                                                        @break
                                                @endswitch
                                                <i class="fas fa-traffic-light"></i> {{ $transaction2->sendstatus_text }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if ($permissions > 0)
                                            <a href="#" onclick="validar('see',{{ $transaction2->id }})"
                                                class="btn btn-success btn-sm" data-bs-toggle="tooltip" data-bs-placement="top"
                                                title="Ver detalles de {{ $transaction2->complete_description }}">Ver detalles <i class="fa fa-eye"></i>
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <br>
                        {{ $transactions2->links() }}
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
    <label class="text-primary">© {{ date_format(date_create(date("Y")),"Y") }} CANAWIL Cambios</label>, todos los derechos reservados.
</div>
@stop

@section('js')
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (window.innerWidth > 768) {
                document.getElementById("type_screen").value = "W";
            } else {
                document.getElementById("type_screen").value = "M";
            }
        });
    </script>
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
<script>
    function validar(xaccion, xid) {
        var identificador = 'nombre' + xid;
        var nombre = '"'+document.getElementById(identificador).value+'"';
        document.getElementById('nombrelimina').value = nombre;
        document.getElementById('toaction').value = xaccion;
        document.getElementById('transaction_id').value = xid;

        if (xaccion == "delete") {
            $('.formeli').submit(function(e) {
                e.preventDefault();
                var delnombre = $('#nombrelimina').val();
                Swal.fire({
                    title: '¡Confirmar!',
                    text: "¿Realmente desea eliminar la Transacción " + delnombre + "?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Eliminar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.submit();
                    }
                })
            });
        } else {
            document.view.submit();
        }
    }
</script>
@stop
