@extends('adminlte::page')

@section('title', 'Pagar a Afiliado Mayorista')

@section('css')
    <link href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
@stop

@section('content_header')
    <h1 class="m-0 text-primary text-center"><b>Afiliado Mayorista: </b><label class="text-dark">{{$users[0]->show_comercial_name}}</label></h1>
@stop

@section('content')
<form action="/transaction" method="POST" id="view" name="view" class="formeli">
    @csrf
    <input type="hidden" id="wholesaler_id" name="wholesaler_id" value="{{$users[0]->id}}">
    <input type="hidden" id="currency_id" name="currency_id" value="{{$users[0]->currency_id}}">
    <input type="hidden" id="currency" name="currency" value="{{$users[0]->currency}}">
    <input type="hidden" id="total_amount" name="total_amount" value="{{$mount_sumwholesaler}}">
    <input type="hidden" id="toaction" name="toaction" value="wholesaler_pay">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header"><i class="fas fa-walking"></i>
                    <b> Transacciones completadas de los afiliados (Aliados Comerciales/Usuarios) de este Mayorista pendientes por pagarle</b>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label for="mount_sumwholesaler"><strong>Monto a pagar (*):</strong></label>
                            <input disabled type="text" class="form-control text-right" style="color: green; background-color: white; font-weight: bold; font-size: 20px;" id="mount_sumwholesaler" name="mount_sumwholesaler" value="{{trim(number_format($mount_sumwholesaler,2,',','.'))}} {{$users[0]->currency}}">
                            <div id="total_amount_error" class="talert" style='display: none;'>
                                <p class="text-danger">Debe pagar un monto mayor a 0</p>
                            </div>
                        </div>
                        <div class="col-md-2 form-group">
                            <label for="payment_date"><strong>Fecha del pago (*):</strong></label>
                            <input disabled type="text" class="form-control" style="background-color: white; font-weight: bold; font-size: 20px;" id="payment_date" name="payment_date" value="{{date('d-m-Y')}}">
                            <input type="hidden" id="real_payment_date" name="real_payment_date" value="{{date('d-m-Y')}}">
                        </div>
                    </div>
                    <div class="col-md-12 form-group">
                        <b>(*) Campos obligatorios</b>
                    </div>
                    <div class="row">
                        <div class="col-md-2 form-group text-center">
                            <button type="submit" onclick="validar()" class="btn btn-success btn-block" tabindex="4">Guardar  <i class="fa fa-save"></i></button>
                        </div>
                        <div class="col-md-2 form-group text-center">
                            <a href="/wholesaler_payment" class="btn btn-secondary btn-block" tabindex="5">Cancelar  <i class="fa fa-arrow-circle-left"></i></a>
                        </div>
                    </div>
                    <div class='row'>
                        <div class="col-12">
                            <br>
                            <table id="tuser" class="table table-striped table-bordered">
                                <thead class="bg-dark text-white">
                                    <tr>
                                        <th class="text-center">ID</th>
                                        <th width="90" class="text-center">Fecha</th>
                                        <th class="text-center">Afiliado</th>
                                        <th width="120" class="text-center">Monto Origen</th>
                                        <th width="120" class="text-center">Mto Origen - Com Cliente</th>
                                        <th class="text-center">Tasa</th>
                                        <th class="text-center">Tasa de compra</th>
                                        <th width="120" class="text-center">Comisión Canawil</th>
                                        <th class="text-center">Porc. May.</th>
                                        <th width="120" class="text-center">Mto. May.</th>
                                        <th class="text-center">P</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach  ($admtransfers as $transfer)
                                    <tr>
                                        <td class="text-left">{{ $transfer->transaction_id }}</td>
                                        <td width="90" class="text-left">{{ date('d-m-Y',strtotime($transfer->transfer_date)) }}</td>
                                        <td class="text-left">{{ $transfer->comercial_name }}</td>
                                        <td width="120" class="text-right">{{ $transfer->gross_amount2 == 0 ? trim($transfer->mount_value_fm) : trim($transfer->gross_amount2_fm) }} {{ $transfer->currency }}</td>
                                        <td width="120" class="text-right">{{ trim($transfer->net_amount_fm) }} {{$transfer->currency}}</td>
                                        <td class="text-right">{{ $transfer->two_decimals == 'Y' ? number_format($transfer->conversion_value,2,',','.') : $transfer->conversion_value }}</td>
                                        <td class="text-right">{{ $transfer->two_decimals == 'Y' ? number_format($transfer->exchange_rate,2,',','.') : $transfer->exchange_rate }}</td>
                                        <td width="120" class="text-right" style="color: blue">{{ trim($transfer->canawil_amount_withheld_fm) }} {{$transfer->currency}}</td>
                                        <td class="text-right">{{ $transfer->two_decimals == 'Y' ? number_format($transfer->wholesaler_commission,2,',','.') : $transfer->wholesaler_commission }}</td>
                                        <td width="120" class="text-right" style="color: green">{{ trim($transfer->wholesaler_amount_fm) }} {{$transfer->currency}}</td>
                                        <td class="text-center">
                                            <input class="form-check-input" type="checkbox" onclick="montoRetenido({{$transfer->wholesaler_amount}}, this.checked)" value="{{$transfer->id}}" id="check{{$transfer->id}}" name="check{{$transfer->id}}" checked>
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
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#tuser').DataTable({
                "lengthMenu": [
                    [5, 10, 25, -1],
                    [5, 10, 25, "Todos"]
                ],
                "order": [
                    [2, "asc"],
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
    function quitaMensaje(){
        $(".talert").css("display", "none");
    }

    function montoRetenido(amount, isChecked){
        // Obtén el valor actual de 'total_amount' y conviértelo a número
        var total_amount = parseFloat(document.getElementById('total_amount').value) || 0;
        var currency = document.getElementById('currency').value;

        // Si el checkbox está marcado, suma el monto; si no, réstalo
        if (isChecked) {
            total_amount += parseFloat(amount);
        } else {
            total_amount -= parseFloat(amount);
        }

        let numeroFormateado = total_amount.toLocaleString('de-DE', { minimumFractionDigits: 2, maximumFractionDigits: 2 });

        // Actualiza el valor de 'total_charge' con el nuevo monto y agrega la cadena '$ USD'
        document.getElementById('mount_sumwholesaler').value = numeroFormateado + ' ' + currency;
        document.getElementById('total_amount').value = total_amount;
    }

    function validar(){

        $('#view').on('submit', function(e) {
            e.preventDefault(); // Evitar el envío predeterminado del formulario

            // Obtén la instancia de DataTables
            var table = $('#tuser').DataTable();

            // Crea un array para almacenar los IDs de los checkboxes seleccionados
            var selected = [];

            // Recorre todas las filas (no solo las visibles)
            table.rows().every(function() {
                var row = this.node();
                var checkbox = $(row).find('input[type="checkbox"]');

                if (checkbox.is(':checked')) {
                    selected.push(checkbox.val()); // Agrega el valor del checkbox seleccionado
                }
            });

            // Verifica si el campo oculto ya existe; si no, lo crea
            var hiddenInput = $('#selected_ids_input');
            if (hiddenInput.length === 0) {
                hiddenInput = $('<input>').attr({
                    type: 'hidden',
                    id: 'selected_ids_input',
                    name: 'selected_ids',
                }).appendTo('#view');
            }

            // Añade los IDs seleccionados al campo oculto
            hiddenInput.val(selected.join(','));

            var xseguir = true;
            var total_amount = parseFloat(document.getElementById('total_amount').value) || 0;
            if  (total_amount <= 0){
                xseguir = false;
                document.getElementById("total_amount_error").style.display = "block";
            }
            if (xseguir){
                // Ahora envía el formulario
                this.submit();
            }
        });
    }
</script>

@stop
