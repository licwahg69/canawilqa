@extends('adminlte::page')

@section('title', 'Pagar Aliados Mayoristas')

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
    <h1 class="m-0 text-primary text-center"><b>Aliados Mayoristas de CANAWIL</b></h1>
@stop

@section('content')
    <form action="/transaction" method="POST" id="view" name="view" class="formeli">
        @csrf
        <input type="hidden" id="wholesaler_id" name="wholesaler_id" value="">
        <input type="hidden" id="toaction" name="toaction" value="">
        <div class="row">
            <div class="col-md-2 form-group text-center">
                <a href="home" class="btn btn-secondary btn-block">Volver <i class="fa fa-arrow-circle-left"></i></a>
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
                                        <th width="30" class="text-center">ID</th>
                                        <th class="text-center">Nombre</th>
                                        <th class="text-center">Correo Electrónico</th>
                                        <th width="70" class="text-center">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($users as $user)
                                        <tr>
                                            <td width="30" class="text-left">{{ $user->id }}</td>
                                            <td class="text-left">{{ $user->name }}</td>
                                            <input type="hidden" id="nombre{{ $user->id }}" value="{{ $user->name }}">
                                            <td class="text-left">{{ $user->email }}</td>
                                            <td width="70" class="text-center">
                                                <a href="#" onclick="validar('wholesaler',{{ $user->id }})"
                                                    class="btn btn-xs btn-default text-success mx-1 shadow"
                                                    title="Pagar a {{ $user->name }}">
                                                    <i class="fas fa-lg fa-fw fa-vote-yea"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div id="datatable2" style='display: none;'>
                            <table id="tuser2">
                                <tbody>
                                    @foreach($users2 as $user2)
                                    <tr>
                                        <td>
                                            <div class="row">
                                                <div>
                                                    <b>ID:</b>
                                                </div>
                                                <div>
                                                    {{ $user2->id }}
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="row">
                                                <div>
                                                    <b>Nombre:</b>
                                                </div>
                                                <div>
                                                    {{ $user2->name }}
                                                </div>
                                            </div>
                                        </td>
                                        <input type="hidden" id="nombre{{ $user2->id }}"
                                                    value="{{ $user2->name }}">
                                        <td>
                                            <div class="row">
                                                <div>
                                                    <b>Correo Electrónico:</b>
                                                </div>
                                                <div>
                                                    {{ $user2->email }}
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <a href="#" onclick="validar('wholesaler',{{ $user2->id }})"
                                                class="btn btn-success btn-sm" data-bs-toggle="tooltip" data-bs-placement="top"
                                                title="Pagar a {{ $user2->name }}">Pagar <i class='fas fa-vote-yea'></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <br>
                            {{ $users2->links() }}
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
    <script>
        function crear() {
            document.getElementById('toaction').value = 'create';
            document.view.submit();
        }

        function validar(xaccion, xid) {
            var identificador = 'nombre' + xid;
            var nombre = '"' + document.getElementById(identificador).value + '"';
            document.getElementById('nombrelimina').value = nombre;
            document.getElementById('toaction').value = xaccion;
            document.getElementById('wholesaler_id').value = xid;

            document.view.submit();
        }
    </script>
@stop
