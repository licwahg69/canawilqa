@extends('adminlte::page')

@section('title', 'Transacciones recibidas')

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
    <h1 class="m-0 text-primary text-center"><b>Transacciones recibidas que no han sido transferidas</b></h1>
@stop

@section('content')
<form action="/transaction" method="POST" id="view" name="view" class="formeli">
    @csrf
    <input type="hidden" id="transaction_id" name="transaction_id" value="">
    <input type="hidden" id="type_screen" name="type_screen" value="">
    <input type="hidden" id="payer_cellphone" name="payer_cellphone" value="{{$payer_cellphone}}">
    <input type="hidden" id="user_cellphone" name="user_cellphone" value="{{$user_cellphone}}">
    <input type="hidden" id="message" name="message" value="{{$message}}">
    <input type="hidden" id="message2" name="message2" value="{{$message2}}">
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
                    <div id="datatable1" style='display: block;'>
                        <table id="tbank" class="table table-striped table-bordered">
                            <thead class="bg-dark text-white">
                                <tr>
                                    <th class="text-center">ID</th>
                                    <th class="text-center">Aliado o Usuario</th>
                                    <th class="text-center">Conversión</th>
                                    <th class="text-center">Descripción</th>
                                    <th width="80" class="text-center">Estatus</th>
                                    <th class="text-center">Pagar</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($transactions as $transaction)
                                    <tr>
                                        @if ($transaction->sendstatus == 'ENV')
                                            <td class="text-left"><b>{{ $transaction->id }}</b></td>
                                            <td class="text-left"><b>{{ $transaction->comercial_name }}</b></td>
                                            <td class="text-left"><b>{{ $transaction->a_to_b }}</b></td>
                                            <td class="text-left"><b>{{ $transaction->complete_description }}</b></td>
                                            <input type="hidden" id="nombre{{ $transaction->id }}"
                                                value="{{ $transaction->complete_description }}">
                                            @switch($transaction->sendstatus)
                                                @case('ENV')
                                                    <td width="80" class="text-center text-primary">
                                                    @break
                                                @case('REC')
                                                    <td width="80" class="text-center text-orange">
                                                    @break
                                                @case('PRO')
                                                    <td width="80" class="text-center text-cyan">
                                                    @break
                                                @case('TRA')
                                                    <td width="80" class="text-center text-danger">
                                                    @break
                                            @endswitch
                                            <b><i class="fas fa-traffic-light"></i> {{ $transaction->sendstatus_text }}</b></td>
                                        @else
                                            <td class="text-left">{{ $transaction->id }}</td>
                                            <td class="text-left">{{ $transaction->comercial_name }}</td>
                                            <td class="text-left">{{ $transaction->a_to_b }}</td>
                                            <td class="text-left">{{ $transaction->complete_description }}</td>
                                            <input type="hidden" id="nombre{{ $transaction->id }}"
                                                value="{{ $transaction->complete_description }}">
                                            @switch($transaction->sendstatus)
                                                @case('ENV')
                                                    <td width="80" class="text-center text-primary">
                                                    @break
                                                @case('REC')
                                                    <td width="80" class="text-center text-orange">
                                                    @break
                                                @case('PRO')
                                                    <td width="80" class="text-center text-cyan">
                                                    @break
                                                @case('TRA')
                                                    <td width="80" class="text-center text-danger">
                                                    @break
                                            @endswitch
                                            <i class="fas fa-traffic-light"></i> {{ $transaction->sendstatus_text }}</td>
                                        @endif
                                        <td class="text-center">
                                            @if ($permissions > 1)
                                                <a href="#" onclick="validar('transfer',{{ $transaction->id }})"
                                                    class="btn btn-xs btn-default text-success mx-1 shadow" title="Transferir a {{ $transaction->complete_description }}">
                                                    <i class="fas fa-lg fa-fw fa-dollar-sign"></i>
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
                                    @if ($transaction2->sendstatus == 'ENV')
                                        <td>
                                            <div class="row">
                                                <div>
                                                    <b>ID:</b>
                                                </div>
                                                <div>
                                                    <b>{{ $transaction2->id }}</b>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="row">
                                                <div>
                                                    <b>Aliado o Usuario:</b>
                                                </div>
                                                <div>
                                                    <b>{{ $transaction2->comercial_name }}</b>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="row">
                                                <div>
                                                    <b>Conversión:</b>
                                                </div>
                                                <div>
                                                    <b>{{ $transaction2->a_to_b }}</b>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="row">
                                                <div>
                                                    <b>Descripción:</b>
                                                </div>
                                                <div>
                                                    <b>{{ $transaction2->complete_description }}</b>
                                                </div>
                                            </div>
                                        </td>
                                        <input type="hidden" id="nombre{{ $transaction2->id }}"
                                                    value="{{ $transaction2->complete_description }}">
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
                                                    <b><i class="fas fa-traffic-light"></i> {{ $transaction2->sendstatus_text }}</b></div>
                                                </div>
                                            </div>
                                        </td>
                                    @else
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
                                                    <b>Aliado o Usuario:</b>
                                                </div>
                                                <div>
                                                    {{ $transaction2->comercial_name }}
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
                                    @endif
                                    <td>
                                        @if ($permissions > 0)
                                            <a href="#" onclick="validar('transfer',{{ $transaction2->id }})"
                                                class="btn btn-success btn-sm" data-bs-toggle="tooltip" data-bs-placement="top"
                                                title="Transferir a {{ $transaction2->complete_description }}">Transferir <i class="fas fa-dollar-sign"></i>
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
            var xpayer_cellphone = document.getElementById("payer_cellphone").value;
            var xmessage = document.getElementById("message").value;
            var xuser_cellphone = document.getElementById("user_cellphone").value;
            var xmessage2 = document.getElementById("message2").value;

            if (xpayer_cellphone.length > 0 && xmessage.length > 0){
                var mensaje = encodeURIComponent(xmessage);

                // Crear la URL para enviar el mensaje
                var url = `https://api.whatsapp.com/send?phone=${xpayer_cellphone}&text=${mensaje}`;

                // Abrir la URL en una nueva ventana sin refrescar la página actual
                window.open(url, '_blank');
            }

            if (xuser_cellphone.length > 0 && xmessage2.length > 0){
                var mensaje2 = encodeURIComponent(xmessage2);

                // Crear la URL para enviar el mensaje
                var url2 = `https://api.whatsapp.com/send?phone=${xuser_cellphone}&text=${mensaje2}`;

                // Abrir WhatsApp en una nueva ventana/pestaña para el dueño de la tienda, después de un breve retraso
                setTimeout(function() {
                    window.open(url2, '_blank');
                }, 2000); // 2 segundo de retraso para asegurar que la primera ventana se abra correctamente
            }
        });
    </script>
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
