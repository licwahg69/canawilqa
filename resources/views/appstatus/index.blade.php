@extends('adminlte::page')

@section('title', 'Mensajes de Estatus')

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
    <style>
        /* Estilo para el checkbox deshabilitado */
.custom-checkbox:disabled {
    position: relative;
    cursor: not-allowed; /* Cambia el cursor para mostrar que está deshabilitado */
}

/* Crear una apariencia personalizada para el checkbox */
.custom-checkbox:disabled:checked::before {
    content: '✔'; /* Puedes usar un símbolo de check o una imagen de fondo */
    color: #007bff; /* Color azul para el check */
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    font-size: 16px; /* Ajusta el tamaño según sea necesario */
}

/* Asegúrate de que el tamaño del checkbox y su contenedor coincidan */
.custom-checkbox {
    width: 20px; /* Ajusta el tamaño del checkbox */
    height: 20px; /* Ajusta el tamaño del checkbox */
    appearance: none; /* Remueve la apariencia predeterminada del checkbox */
    background-color: #e9ecef; /* Color de fondo del checkbox deshabilitado */
    border: 1px solid #adb5bd; /* Color del borde del checkbox */
    border-radius: 3px; /* Bordes redondeados del checkbox */
    display: inline-block;
    position: relative;
    cursor: not-allowed; /* Cursor para indicar deshabilitado */
}

.custom-checkbox:checked {
    background-color: #007bff; /* Color del fondo cuando está marcado */
    border-color: #007bff; /* Color del borde cuando está marcado */
}
    </style>
@stop

@section('content_header')
    <h1 class="m-0 text-primary text-center"><b>Mensajes de Estatus del Sistema Canawil</b></h1>
@stop

@section('content')
<form action="/sys_status" method="POST" id="view" name="view" class="formeli">
    @csrf
    <input type="hidden" id="appstatus_id" name="appstatus_id" value="">
    <input type="hidden" id="toaction" name="toaction" value="">
    <div class="row">
        @if ($permissions > 1)
            <div class="col-md-2 form-group text-center">
                <a href="#" onclick="crear()" class="btn btn-primary btn-block">Agregar Nuevo  <i class='fa fa-plus'></i></a>
            </div>
        @endif
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
                                    <th class="text-center">Mensaje</th>
                                    <th width="70" class="text-center">Detiene la APP</th>
                                    <th width="100" class="text-center">Puede ser borrado</th>
                                    <th width="40" class="text-center">Activo</th>
                                    <th width="60" class="text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($app_statuses as $app_status)
                                    <tr>
                                        <td width="30" class="text-left">{{ $app_status->id }}</td>
                                        <td class="text-left">{{ $app_status->message }}</td>
                                        <td width="70" class="text-center">{{ $app_status->stop }}</td>
                                        <td width="100" class="text-center">{{ $app_status->can_delete }}</td>
                                        <input type="hidden" id="nombre{{ $app_status->id }}"
                                            value="{{ $app_status->message }}">
                                        @if ($app_status->active == 'Y')
                                            <td width="40" class="text-center"><input disabled class="form-check-input custom-checkbox" type="checkbox" value="" checked></td>
                                        @else
                                            <td width="40" class="text-center"><input disabled class="form-check-input custom-checkbox" type="checkbox" value=""></td>
                                        @endif
                                        <td width="60" class="text-center">
                                            @if ($permissions > 2 && $app_status->setting == "NON" && $app_status->active == "N")
                                                <button type="submit" onclick="validar('edit',{{ $app_status->id }})"
                                                    class="btn btn-xs btn-default text-primary mx-1 shadow" title="Activar {{ $app_status->message }}">
                                                    <i class="fa fa-lg fa-fw fa-check"></i>
                                                </button>
                                            @endif
                                            @if ($permissions > 2 && $app_status->setting == "APP" && $app_status->active == "N")
                                                <button type="submit" onclick="validar('edit',{{ $app_status->id }})"
                                                    class="btn btn-xs btn-default text-primary mx-1 shadow" title="Activar {{ $app_status->message }}">
                                                    <i class="fa fa-lg fa-fw fa-check"></i>
                                                </button>
                                            @endif
                                            @if ($permissions > 3 && $app_status->can_delete == "SI")
                                                <button type="submit" onclick="validar('delete',{{ $app_status->id }})"
                                                    class="btn btn-xs btn-default text-danger mx-1 shadow" title="Eliminar {{ $app_status->message }}">
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
                                @foreach($app_statuses2 as $app_status2)
                                <tr>
                                    <td>
                                        <div class="row">
                                            <div>
                                                <b>ID:</b>
                                            </div>
                                            <div>
                                                {{ $app_status2->id }}
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="row">
                                            <div>
                                                <b>Mensaje:</b>
                                            </div>
                                            <div>
                                                {{ $app_status2->message }}
                                            </div>
                                        </div>
                                    </td>
                                    <input type="hidden" id="nombre{{ $app_status2->id }}"
                                                value="{{ $app_status2->message }}">
                                    <td>
                                        <div class="row">
                                            <div>
                                                <b>Detiene la APP:</b>
                                            </div>
                                            <div>
                                                {{ $app_status2->stop }}
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="row">
                                            <div>
                                                <b>Puede ser borrado:</b>
                                            </div>
                                            <div>
                                                {{ $app_status2->can_delete }}
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="row">
                                            <div>
                                                <b>Activo:</b>
                                            </div>
                                            <div>
                                                @if ($app_status2->active == 'Y')
                                                    <input disabled class="form-check-input custom-checkbox" type="checkbox" value="" checked>
                                                @else
                                                    <input disabled class="form-check-input custom-checkbox" type="checkbox" value="">
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if ($permissions > 2 && $app_status2->setting == "NON" && $app_status2->active == "N")
                                            <button type="submit" onclick="validar('edit',{{ $app_status2->id }})"
                                                class="btn btn-primary btn-sm" title="Activar {{ $app_status2->message }}">Activar
                                                <i class="fa fa-check"></i>
                                            </button>
                                        @endif
                                        @if ($permissions > 2 && $app_status2->setting == "APP" && $app_status2->active == "N")
                                            <button type="submit" onclick="validar('edit',{{ $app_status2->id }})"
                                                class="btn btn-primary btn-sm" title="Activar {{ $app_status2->message }}">Activar
                                                <i class="fa fa-check"></i>
                                            </button>
                                        @endif
                                        @if ($permissions > 3 && $app_status2->can_delete == "SI")
                                            <button type="submit" onclick="validar('delete',{{ $app_status2->id }})"
                                                class="btn btn-danger btn-sm" data-bs-toggle="tooltip"
                                                data-bs-placement="top" title="Eliminar {{ $app_status2->message }}">Eliminar <i
                                                    class="fa fa-trash"></i></button>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <br>
                        {{ $app_statuses2->links() }}
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
        document.getElementById('appstatus_id').value = xid;

        // Desvincular cualquier evento submit previo para evitar conflictos
        $('.formeli').off('submit').on('submit', function(e) {
            e.preventDefault();

            var delnombre = $('#nombrelimina').val();
            var mensaje = "";
            var confirmButtonText = "";

            if (xaccion == "delete") {
                mensaje = "¿Realmente desea eliminar el mensaje " + delnombre + "?";
                confirmButtonText = "Eliminar";
            } else if (xaccion == "edit") {
                mensaje = "¿Desea fijar como activo el mensaje " + delnombre + "?";
                confirmButtonText = "Activar";
            }

            Swal.fire({
                title: '¡Confirmar!',
                text: mensaje,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: xaccion == "delete" ? '#d33' : '#3085d6',
                cancelButtonColor: xaccion == "delete" ? '#3085d6' : '#d33',
                confirmButtonText: confirmButtonText,
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submit();
                }
            });
        });
    }

</script>
@stop
