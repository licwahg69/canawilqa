@extends('adminlte::page')

@section('title', 'Reenviar Imagen')

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
    <h1 class="m-0 text-primary text-center"><b>Transacciones no completadas por que falta la imagen</b></h1>
@stop

@section('content')
<form action="/resend_image" method="POST" id="view" name="view" class="formeli">
    @csrf
    <input type="hidden" id="transaction_id" name="transaction_id" value="">
    <input type="hidden" id="type_screen" name="type_screen" value="">
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
                        <table id="tbank" class="table table-striped table-bordered">
                            <thead class="bg-dark text-white">
                                <tr>
                                    <th class="text-center">ID</th>
                                    <th class="text-center">Conversión</th>
                                    <th class="text-center">Descripción</th>
                                    <th class="text-center">Pagador</th>
                                    <th width="120" class="text-center">Monto a Cambiar</th>
                                    <th width="120" class="text-center">Monto a Pagar</th>
                                    <th width="70" class="text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($transactions as $transaction)
                                    <tr>
                                        <td class="text-left">{{ $transaction->id }}</td>
                                        <td class="text-left">{{ $transaction->a_to_b }}</td>
                                        <td class="text-left">{{ $transaction->complete_description }}</td>
                                        <td class="text-left">{{ $transaction->payer_name }}</td>
                                        <input type="hidden" id="nombre{{ $transaction->id }}"
                                            value="{{ $transaction->complete_description }}">
                                        <td width="120" class="text-right">{{ trim($transaction->mount_value_fm) }} {{$transaction->currency}}</td>
                                        <td width="120" class="text-right">{{ trim($transaction->mount_change_fm) }} {{$transaction->currency2}}</td>
                                        <td width="70" class="text-center">
                                            @if ($permissions > 2)
                                                <a href="#" onclick="validar('resend',{{ $transaction->id }})"
                                                    class="btn btn-xs btn-default text-success mx-1 shadow" title="Reenviar Imagen {{ $transaction->complete_description }}">
                                                    <i class="fas fa-lg fa-fw fa-camera"></i>
                                                </a>
                                            @endif
                                            @if ($permissions > 3)
                                                <button type="submit" onclick="validar('delete',{{ $transaction->id }})"
                                                    class="btn btn-xs btn-default text-danger mx-1 shadow" title="Eliminar {{ $transaction->complete_description }}">
                                                    <i class="fa fa-lg fa-fw fa-trash"></i>
                                                </button>
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
                                        @if ($permissions > 2)
                                            <a href="#" onclick="validar('resend',{{ $transaction2->id }})"
                                                class="btn btn-success btn-sm" data-bs-toggle="tooltip" data-bs-placement="top"
                                                title="Reenviar imagen a {{ $transaction2->complete_description }}">Reenviar Imagen <i class="fas fa-camera"></i>
                                            </a>
                                        @endif
                                        @if ($permissions > 3)
                                            <button type="submit" onclick="validar('delete',{{ $transaction2->id }})"
                                                class="btn btn-danger btn-sm" data-bs-toggle="tooltip"
                                                data-bs-placement="top" title="Eliminar {{ $transaction2->complete_description }}">Eliminar <i
                                                    class="fa fa-trash"></i></button>
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
    function crear() {
        document.getElementById('toaction').value = 'create';
        document.view.submit();
    }
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
