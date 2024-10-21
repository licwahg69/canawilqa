@extends('adminlte::page')

@section('title', 'Bancos Destino')

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
    <h1 class="m-0 text-primary text-center"><b>Bancos Destino</b></h1>
@stop

@section('content')
<form action="/currencybank" method="POST" id="view" name="view" class="formeli">
    @csrf
    <input type="hidden" id="currencybank_id" name="currencybank_id" value="">
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
                                    <th class="text-center">Banco Destino</th>
                                    <th class="text-center">Número de cuenta</th>
                                    <th width="60" class="text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($currency_banks as $currency_bank)
                                    <tr>
                                        <td width="30" class="text-left">{{ $currency_bank->id }}</td>
                                        <td class="text-left">{{ $currency_bank->countryname }}</td>
                                        <td class="text-left">{{ $currency_bank->bankname }}</td>
                                        <input type="hidden" id="nombre{{ $currency_bank->id }}"
                                            value="{{ $currency_bank->bankname }}">
                                        <td class="text-left">{{ $currency_bank->account_number }}</td>
                                        <td width="60" class="text-center">
                                            @if ($permissions > 2)
                                                <a href="#" onclick="validar('edit',{{ $currency_bank->id }})"
                                                    class="btn btn-xs btn-default text-primary mx-1 shadow" title="Editar {{ $currency_bank->bankname }}">
                                                    <i class="fa fa-lg fa-fw fa-pen"></i>
                                                </a>
                                            @endif
                                            @if ($permissions > 3)
                                                <button type="submit" onclick="validar('delete',{{ $currency_bank->id }})"
                                                    class="btn btn-xs btn-default text-danger mx-1 shadow" title="Eliminar {{ $currency_bank->bankname }}">
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
                                @foreach($currency_banks2 as $currency_bank2)
                                <tr>
                                    <td>
                                        <div class="row">
                                            <div>
                                                <b>ID:</b>
                                            </div>
                                            <div>
                                                {{ $currency_bank2->id }}
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="row">
                                            <div>
                                                <b>País:</b>
                                            </div>
                                            <div>
                                                {{ $currency_bank2->countryname }}
                                            </div>
                                        </div>
                                    </td>
                                    <input type="hidden" id="nombre{{ $currency_bank2->id }}"
                                                value="{{ $currency_bank2->bankname }}">
                                    <td>
                                        <div class="row">
                                            <div>
                                                <b>Banco:</b>
                                            </div>
                                            <div>
                                                {{ $currency_bank2->bankname }}
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="row">
                                            <div>
                                                <b>Número de cuenta:</b>
                                            </div>
                                            <div>
                                                {{ $currency_bank2->account_number }}
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if ($permissions > 2)
                                            <a href="#" onclick="validar('edit',{{ $currency_bank2->id }})"
                                                class="btn btn-primary btn-sm" data-bs-toggle="tooltip" data-bs-placement="top"
                                                title="Editar {{ $currency_bank2->bankname }}">Editar <i class='fa fa-pen'></i>
                                            </a>
                                        @endif
                                        @if ($permissions > 3)
                                            <button type="submit" onclick="validar('delete',{{ $currency_bank2->id }})"
                                                class="btn btn-danger btn-sm" data-bs-toggle="tooltip"
                                                data-bs-placement="top" title="Eliminar {{ $currency_bank2->bankname }}">Eliminar <i
                                                    class="fa fa-trash"></i></button>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <br>
                        {{ $currency_banks2->links() }}
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
        document.getElementById('currencybank_id').value = xid;

        if (xaccion == "delete") {
            $('.formeli').submit(function(e) {
                e.preventDefault();
                var delnombre = $('#nombrelimina').val();
                Swal.fire({
                    title: '¡Confirmar!',
                    text: "¿Realmente desea eliminar el Banco Destino " + delnombre + "?",
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
