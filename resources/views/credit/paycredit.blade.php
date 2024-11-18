@extends('adminlte::page')

@section('title', 'Créditos pagados')

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
    <h1 class="m-0 text-primary text-center"><b>Créditos pagados </b></h1>
@stop

@section('content')
<form action="/credit" method="POST" id="view" name="view" class="formeli">
    @csrf
    <input type="hidden" id="type_screen" name="type_screen" value="">
    <input type="hidden" id="user_id" name="user_id" value="">
    <input type="hidden" id="toaction" name="toaction" value="see_paycredit">
    <div class="row">
        <div class="col-md-2 form-group text-center">
            <a href="home" class="btn btn-secondary btn-block">Volver  <i class="fa fa-arrow-circle-left"></i></a>
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
                                            <th class="text-center">ID</th>
                                            <th class="text-center">Cliente</th>
                                            <th class="text-center">Ver detalles</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($datos as $dato)
                                            <tr>
                                                <td class="text-left">
                                                    <label style="font-size:20px">{{$dato['user_id']}}</label>
                                                </td>
                                                <td class="text-left">
                                                    <label style="font-size:20px">{{$dato['user_name']}}</label>
                                                </td>
                                                <td class="text-center">
                                                    @if ($permissions > 0)
                                                        <a href="#" onclick="validar({{ $dato['user_id'] }})"
                                                            class="btn btn-success btn-sm" title="Ver detalles">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                    @endif
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
                                @foreach ($datos as $dato)
                                    <tr>
                                        <td>
                                            <div class="row">
                                                <div>
                                                    <b>ID:</b>
                                                </div>
                                                <div>
                                                    {{ $dato['user_id'] }}
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="row">
                                                <div>
                                                    <b>Cliente:</b>
                                                </div>
                                                <div>
                                                    {{ $dato['user_name'] }}
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            @if ($permissions > 0)
                                                <a href="#" onclick="validar({{ $dato['user_id'] }})"
                                                    class="btn btn-success btn-sm" data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="Ver detalles">Ver detalles <i class="fa fa-eye"></i>
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
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
                $('#datatable3').css('display', 'block');
                $('#datatable4').css('display', 'none');
            } else {
                $('#datatable3').css('display', 'none');
                $('#datatable4').css('display', 'block');
            }
        });
    </script>
<script>
    function validar(xid) {
        document.getElementById('user_id').value = xid;

        document.view.submit();
    }
</script>
@stop
