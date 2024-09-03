@extends('adminlte::page')

@section('title', 'Banco')

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
    <h1 class="m-0 text-primary text-center"><b>Bancos</b></h1>
@stop

@section('content')
<form action="/bank" method="POST" id="view" name="view" class="formeli">
    @csrf
    <input type="hidden" id="bank_id" name="bank_id" value="">
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
                                    <th class="text-center">País</th>
                                    <th class="text-center">Banco</th>
                                    <th width="40" class="text-center">Prefijo</th>
                                    <th width="60" class="text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($banks as $bank)
                                    <tr>
                                        <td width="30" class="text-left">{{ $bank->id }}</td>
                                        <td class="text-left">{{ $bank->countryname }}</td>
                                        <td class="text-left">{{ $bank->bankname }}</td>
                                        <input type="hidden" id="nombre{{ $bank->id }}"
                                            value="{{ $bank->bankname }}">
                                        <td width="40" class="text-left">{{ $bank->prefix }}</td>
                                        <td width="60" class="text-center">
                                            @if ($permissions > 2)
                                                <a href="#" onclick="validar('edit',{{ $bank->id }})"
                                                    class="btn btn-xs btn-default text-primary mx-1 shadow" title="Editar {{ $bank->bankname }}">
                                                    <i class="fa fa-lg fa-fw fa-pen"></i>
                                                </a>
                                            @endif
                                            @if ($permissions > 3)
                                                <button type="submit" onclick="validar('delete',{{ $bank->id }})"
                                                    class="btn btn-xs btn-default text-danger mx-1 shadow" title="Eliminar {{ $bank->bankname }}">
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
                                @foreach($banks2 as $bank2)
                                <tr>
                                    <td>
                                        <div class="row">
                                            <div>
                                                <b>ID:</b>
                                            </div>
                                            <div>
                                                {{ $bank2->id }}
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="row">
                                            <div>
                                                <b>País:</b>
                                            </div>
                                            <div>
                                                {{ $bank2->countryname }}
                                            </div>
                                        </div>
                                    </td>
                                    <input type="hidden" id="nombre{{ $bank2->id }}"
                                                value="{{ $bank2->bankname }}">
                                    <td>
                                        <div class="row">
                                            <div>
                                                <b>Banco:</b>
                                            </div>
                                            <div>
                                                {{ $bank2->bankname }}
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="row">
                                            <div>
                                                <b>Prefijo:</b>
                                            </div>
                                            <div>
                                                {{ $bank2->prefix }}
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if ($permissions > 2)
                                            <a href="#" onclick="validar('edit',{{ $bank2->id }})"
                                                class="btn btn-primary btn-sm" data-bs-toggle="tooltip" data-bs-placement="top"
                                                title="Editar {{ $bank2->bankname }}">Editar <i class='fa fa-pen'></i>
                                            </a>
                                        @endif
                                        @if ($permissions > 3)
                                            <button type="submit" onclick="validar('delete',{{ $bank2->id }})"
                                                class="btn btn-danger btn-sm" data-bs-toggle="tooltip"
                                                data-bs-placement="top" title="Eliminar {{ $bank2->bankname }}">Eliminar <i
                                                    class="fa fa-trash"></i></button>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <br>
                        {{ $banks2->links() }}
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
                        [1, "asc"],
                        [2, "asc"]
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
        document.getElementById('bank_id').value = xid;

        if (xaccion == "delete") {
            $('.formeli').submit(function(e) {
                e.preventDefault();
                var delnombre = $('#nombrelimina').val();
                Swal.fire({
                    title: '¡Confirmar!',
                    text: "¿Realmente desea eliminar el Banco " + delnombre + "?",
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
