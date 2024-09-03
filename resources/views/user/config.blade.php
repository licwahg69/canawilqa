@extends('adminlte::page')

@section('title', 'Parametrizar Usuario')

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
    <h1 class="m-0 text-primary text-center"><b>Parametrizar Usuarios</b></h1>
@stop

@section('content')
<form action="/user" method="POST" id="view" name="view" class="formeli">
    @csrf
    @php
        $userrole = auth()->user()->role;
        $puserid = auth()->user()->id;
    @endphp
    <input type="hidden" id="user_id" name="user_id" value="">
    <input type="hidden" id="role" name="role" value="">
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
                        <table id="tuser" class="table table-striped table-bordered display responsive nowrap">
                            <thead class="bg-dark text-white">
                                <tr>
                                    <th width="30" class="text-center">ID</th>
                                    <th class="text-center">Nombre</th>
                                    <th width="150" class="text-center">Rol en el Sistema</th>
                                    <th width="70" class="text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                    <tr>
                                        <td width="30" class="text-left">{{ $user->id }}</td>
                                        <td class="text-left">{{ $user->name }}</td>
                                        <td width="150" class="text-left">{{ $user->role_name }}</td>
                                        <td width="70" class="text-center">
                                            @if ($permissions > 2)
                                                @if ($userrole == 'ADM' && $puserid == 1 && $user->id == 1)
                                                    <a href="#" onclick="validar('config',{{ $user->id }})"
                                                        class="btn btn-xs btn-default text-primary mx-1 shadow" title="Parametrizar a {{ $user->name }}">
                                                        <i class="fa fa-lg fa-fw fa-pen"></i>
                                                    </a>
                                                @else
                                                    @if ($user->id > 1)
                                                        <a href="#" onclick="validar('config',{{ $user->id }})"
                                                            class="btn btn-xs btn-default text-primary mx-1 shadow" title="Parametrizar a {{ $user->name }}">
                                                            <i class="fa fa-lg fa-fw fa-pen"></i>
                                                        </a>
                                                    @endif
                                                @endif
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
                                                <b>Rol en el Sistema:</b>
                                            </div>
                                            <div>
                                                {{ $user2->role_name }}
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if ($permissions > 2)
                                            <a href="#" onclick="validar('config',{{ $user2->id }})"
                                                class="btn btn-primary btn-sm" data-bs-toggle="tooltip" data-bs-placement="top"
                                                title="Parametrizar a {{ $user2->name }}">Editar <i class='fa fa-pen'></i>
                                            </a>
                                        @endif
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
</form>
<form action="/force-logout" id="forcelogout" name="forcelogout" method="post">
    @csrf
    <input type="hidden" id="href" name="href" value="">
</form>
@stop

@section('footer')
<div id="copyrigth" class="float-right d-sm-inline">
    <label class="text-primary">© {{ date_format(date_create(date("Y")),"Y") }} CANAWIL Cambios</label>, todos los derechos reservados.
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
            } else {
                $('#datatable1').css('display', 'none');
                $('#datatable2').css('display', 'block');
            }
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
        });
    </script>
<script>
    function validar(xaccion, xid) {
        document.getElementById('toaction').value = xaccion;
        document.getElementById('user_id').value = xid;

        document.view.submit();
    }
</script>
@stop

