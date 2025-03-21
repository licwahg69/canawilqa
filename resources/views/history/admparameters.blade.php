@extends('adminlte::page')

@section('title', 'Histórico de Transacciones')

@section('content_header')
    <h1 class="m-0 text-primary text-center"><b>Histórico de Transacciones</b></h1>
@stop

@section('content')
<form action="/history" method="POST" id="view" name="view" class="formeli">
    @csrf
    <input type="hidden" id="toaction" name="toaction" value="report">
    <div class="row">
        <div class="col-md-2 form-group text-center">
            <a href="home" class="btn btn-secondary btn-block">Volver  <i class="fa fa-arrow-circle-left"></i></a>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-calculator"></i> <b>Parametros para solicitar la consulta al Histórico de transacciones</b>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-2 form-group">
                            <label for="from_auth_date">Fecha Desde (*):</label>
                            <input type="date" class="form-control" id="from_auth_date" name="from_auth_date" onchange="timepicker(this.id)" onclick="quitaMensaje()"/>
                            <div id="from_auth_date_error" class="talert" style='display: none;'>
                                <p class="text-danger">La Fecha Desde es requerida</p>
                            </div>
                        </div>
                        <div class="col-md-2 form-group">
                            <label for="to_auth_date">Fecha Hasta (*):</label>
                            <input type="date" class="form-control" id="to_auth_date" name="to_auth_date" onchange="timepicker(this.id)" onclick="quitaMensaje()"/>
                            <div id="to_auth_date_error" class="talert" style='display: none;'>
                                <p class="text-danger">La Fecha Hasta es requerida</p>
                            </div>
                        </div>
                        <div class="col-md-8 form-group">
                            <label for="user_id">Aliado/Usuario:</label>
                            <select id="user_id" name="user_id" class="form-control">
                                <option value="ALL">Todos</option>
                                @foreach ($users as $user)
                                    <option value="{{$user->id}}">{{$user->show_comercial_name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-12 form-group">
                        <b>(*) Campos obligatorios</b>
                    </div>
                    <br>
                    @if ($permissions > 0)
                        <div class="col-md-2 form-group text-center">
                            <a href="#" onclick="validar()" class="btn btn-success btn-block">Ver Reporte  <i class='fa fa-eye'></i></a>
                        </div>
                    @endif
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
<script>
    function quitaMensaje(){
        $(".talert").css("display", "none");
    }

    function timepicker(xid){
        var fecha;
        var input = $('#'+xid);
        if (Modernizr.inputtypes.date===true) {
            fecha = input.prop('valueAsDate');
            fecha.setMinutes(fecha.getMinutes()+fecha.getTimezoneOffset());
        } else {
            fecha = input.datepicker('getDate');
        }
    }

    function validar() {
        var xseguir = true;
        var xfrom_auth_date = document.getElementById("from_auth_date").value;
        if  (xfrom_auth_date.length < 1){
            xseguir = false;
            document.getElementById("from_auth_date_error").style.display = "block";
        }
        var xto_auth_date = document.getElementById("to_auth_date").value;
        if  (xto_auth_date.length < 1){
            xseguir = false;
            document.getElementById("to_auth_date_error").style.display = "block";
        }
        if (xseguir){
            document.view.submit();
        }
    }

</script>
@stop
