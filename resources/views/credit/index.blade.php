@extends('adminlte::page')

@section('title', 'Pagar Créditos')

@section('css')
    <link href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
@stop

@section('content_header')
    <h1 class="m-0 text-primary text-center"><b>Pagar Créditos pendientes</b></h1>
@stop

@section('content')
<style>
    #pasteArea {
        border: 2px dashed #ccc;
        width: 600px;
        height: 300px;
        display: flex;
        justify-content: center;
        align-items: center;
        text-align: center;
        margin-left: 50px;
    }

    #imagen {
        display: none;
        margin-top: 20px;
    }

    @media (max-width: 768px) {
        /* Estilos para dispositivos móviles */
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
<form action="/credit" method="POST" id="view" name="view" class="formeli">
    @csrf
    <input type="hidden" id="total_amount" name="total_amount" value="0">
    <input type="hidden" id="orientation" name="orientation" value="">
    <input type="hidden" id="imageData" name="imageData">
    <input type="hidden" id="toaction" name="toaction" value="pay">

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-list"></i><b> Lista de Créditos por pagar</b>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 form-group">
                            <label for="total_charge"><strong>Monto total del pago (*):</strong></label>
                            <input disabled type="text" class="form-control text-right" style="color: blue; background-color: white; font-weight: bold; font-size: 20px;" id="total_charge" name="total_charge" value="0,00">
                            <div id="total_charge_error" class="talert" style='display: none;'>
                                <p class="text-danger">El monto total del pago es requerido</p>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <br>
                    <div id="datatable3" style='display: block;'>
                        <table id="tuser" class="table table-striped table-bordered display responsive nowrap">
                            <thead class="bg-dark text-white">
                                <tr>
                                    <th width="30" class="text-center">ID</th>
                                    <th class="text-center">Cliente</th>
                                    <th class="text-center">Cuenta</th>
                                    <th class="text-center">Banco</th>
                                    <th class="text-center">Fecha</th>
                                    <th width="200" class="text-center">Monto adeudado</th>
                                    <th width="70" class="text-center">Marcar</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach  ($credits as $credit)
                                    <tr>
                                        <td width="30" class="text-left">{{ $credit->transaction_id }}</td>
                                        <td class="text-left">{{ $credit->account_holder }}</td>
                                        <td class="text-left">{{ $credit->account_number }}</td>
                                        <td class="text-left">{{ $credit->bankname }}</td>
                                        <td class="text-left">{{ date('d-m-Y',strtotime($credit->date_debt)) }}</td>
                                        <td width="200" class="text-right">{{ trim($credit->net_amount_fm) }}{{$credit->symbol}} {{$credit->currency}}</td>
                                        <td width="70" class="text-center">
                                            <input class="form-check-input" type="checkbox" onclick="montoRetenido({{$credit->net_amount}}, this.checked), quitaMensaje()" value="{{$credit->id}}" id="check{{$credit->id}}" name="check{{$credit->id}}">
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div id="datatable4" style='display: none;'>
                        <table id="tbank4">
                            <tbody>
                                @foreach ($credits2 as $credit2)
                                    <tr>
                                        <td>
                                            <div class="row">
                                                <div>
                                                    <b>ID:</b>
                                                </div>
                                                <div>
                                                    {{ $credit2->transaction_id }}
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="row">
                                                <div>
                                                    <b>Cliente:</b>
                                                </div>
                                                <div>
                                                    {{ $credit2->account_holder }}
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="row">
                                                <div>
                                                    <b>Cuenta:</b>
                                                </div>
                                                <div>
                                                    {{ $credit2->account_number }}
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="row">
                                                <div>
                                                    <b>Banco:</b>
                                                </div>
                                                <div>
                                                    {{ $credit2->bankname }}
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="row">
                                                <div>
                                                    <b>Fecha:</b>
                                                </div>
                                                <div>
                                                    <b>{{ date('d-m-Y',strtotime($credit2->date_debt)) }}</b>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="row">
                                                <div>
                                                    <b>Monto adeudado:</b>
                                                </div>
                                                <div style="color: red">
                                                    <b>{{ trim($credit2->net_amount_fm) }}{{$credit2->symbol}} {{$credit2->currency}} </b>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="row">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" onclick="montoRetenido({{$credit2->net_amount}}, this.checked), quitaMensaje()" value="{{$credit2->id}}" id="check{{$credit2->id}}" name="check{{$credit2->id}}">
                                                    <label class="form-check-label" for="check{{$credit2->id}}">
                                                        <b>Marcar</b>
                                                    </label>
                                                </div>
                                            </div>
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
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-money-check-alt"></i><b> Detalles del Pago</b>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label for="canawilbank_id" class="form-label"><b>Banco de Canawil (*):</b></label>
                            <select id="canawilbank_id" name="canawilbank_id" class="form-control" onchange="getAccount(this.value)" onclick="quitaMensaje()">
                                <option value="">Seleccionar</option>
                                @foreach ($canawil_banks as $canawil_bank)
                                    <option value="{{$canawil_bank->id}}">{{$canawil_bank->bank_name}}</option>
                                @endforeach
                            </select>
                            <div id="canawilbank_id_error" class="talert" style='display: none;'>
                                <p class="text-danger">El Banco de Canawil es requerido</p>
                            </div>
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="canawil_account_number" class="form-label"><b>Cuenta:</b></label>
                            <input disabled class="form-control" type="text" id="canawil_account_number" name="canawil_account_number" value="" placeholder="Cuenta">
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="send_way" class="form-label"><b>Medio para el envío:</b></label>
                            <input disabled class="form-control" type="text" id="send_way" name="send_way" value="" placeholder="Medio para el envío">
                        </div>
                    </div>
                    <hr>
                    <h5 class="text-center"><b> Agregar capture del pago bancario hecho usando Win + Shift + S (*)</b></h5>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-2 form-group">
                            </div>
                            <div class="col-md-8 form-group">
                                <!-- Div donde se pegará la imagen -->
                                <div id="pasteArea">
                                    Pega aquí tu imagen (Ctrl + V)
                                </div>

                                <!-- Imagen pegada será mostrada aquí -->
                                <img id="imagen" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 form-group">
                                <label class="control-label">Nombre del Archivo:</label>
                                <input readonly class="form-control" type="text" id="linkaddress_i"
                                    name="linkaddress_i" value="" maxlength='250'>
                                <div id="transaction_error" class="talert" style='display: none;'>
                                    <p class="text-danger">Debe hacer un capture para guardarla con el pago</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 form-group">
                        <b>(*) Campos obligatorios</b>
                    </div>
                    <div class="row">
                        <div class="col-md-2 form-group text-center">
                            <button type="submit" onclick="validar()" class="btn btn-success btn-block">Guardar  <i class="fa fa-save"></i></button>
                        </div>
                        <div class="col-md-2 form-group text-center">
                            <a href="/home" class="btn btn-secondary btn-block">Cancelar  <i class="fa fa-arrow-circle-left"></i></a>
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
        // Función para ajustar la imagen pegada, calcular su orientación y tamaño
        function ajustarImagen(imgSrc) {
            var linkadress = document.getElementById('linkaddress_i');
            var imagen = document.getElementById('imagen');
            var img = new Image();
            img.src = imgSrc;

            img.onload = function() {
                var width = img.width;
                var height = img.height;

                var aspectRatioWidth = width / height;
                var aspectRatioHeight = height / width;

                var orientation;
                if (Math.abs(aspectRatioWidth - aspectRatioHeight) < 0.3) {
                    orientation = 'CUA';  // Cuadrada
                } else if (aspectRatioWidth > aspectRatioHeight) {
                    orientation = 'HOR';  // Horizontal
                } else {
                    orientation = 'VER';  // Vertical
                }

                document.getElementById("orientation").value = orientation;

                // Ajustar tamaño según la orientación
                imagen.src = imgSrc;
                switch (orientation) {
                    case 'VER':
                        imagen.style.width = "350px";
                        imagen.style.height = "600px";
                        imagen.style.marginLeft= "150px";
                        break;
                    case 'CUA':
                        imagen.style.width = "400px";
                        imagen.style.height = "400px";
                        imagen.style.marginLeft= "130px";
                        break;
                    case 'HOR':
                        imagen.style.width = "600px";
                        imagen.style.height = "300px";
                        imagen.style.marginLeft= "50px";
                        break;
                }
                const timestamp = Date.now(); // Obtiene el tiempo actual en milisegundos
                const nombreBase = 'prtscrn'; // Puedes cambiar esto a lo que desees
                const extension = 'png'; // Como estamos usando capturas, el formato es PNG

                linkadress.value = `${nombreBase}_${timestamp}.${extension}`;

                imagen.style.display = 'block'; // Mostrar la imagen
                document.getElementById('pasteArea').style.display = 'none'; // Ocultar el área de pegado
            };
        }

        // Evento para pegar una imagen en el área designada
        document.addEventListener('DOMContentLoaded', function() {
            const pasteArea = document.getElementById('pasteArea');

            pasteArea.addEventListener('paste', function(event) {
                const items = event.clipboardData.items;

                for (let i = 0; i < items.length; i++) {
                    const item = items[i];

                    if (item.type.indexOf('image') !== -1) {
                        const file = item.getAsFile();
                        const reader = new FileReader();

                        reader.onload = function(e) {
                            // Enviar la imagen en base64 al servidor
                            document.getElementById('imageData').value = e.target.result;

                            ajustarImagen(e.target.result); // Llamar la función para ajustar imagen
                        };

                        reader.readAsDataURL(file); // Leer imagen como base64
                    }
                }
            });
        });
    </script>
<script>
    function quitaMensaje(){
        $(".talert").css("display", "none");
    }

    function getAccount(xid){
        fetch(`/account/${xid}`)
            .then(response => response.json())
            .then(jsondata => showAccount(jsondata))
    }

    function showAccount(jsondata){
        let account_number = jsondata.account_number;
        let description = jsondata.description;

        if (account_number.length > 4) {
            // Obtener los últimos 4 dígitos
            let ultimos4 = account_number.slice(-4);
            // Reemplazar el resto con "x"
            let enmascarado = "X".repeat(account_number.length - 4) + ultimos4;
            // Mostrar el valor enmascarado en el input
            document.getElementById("canawil_account_number").value = enmascarado;
        }

        document.getElementById("send_way").value = description;
    }

    function formatFloat(value) {
        // Convierte el valor a un número flotante
        let num = parseFloat(value);

        // Si todos los decimales son 0, muestra solo dos decimales
        if (Number.isInteger(num)) {
            return num.toFixed(2).replace('.', ',').replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }

        // Convierte el número a cadena para formatearlo
        let parts = num.toString().split('.'); // Divide en parte entera y parte decimal

        // Añade separadores de miles a la parte entera
        parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, '.');

        // Si hay parte decimal, reemplaza el punto por coma y elimina ceros finales
        if (parts[1]) {
            parts[1] = parts[1].replace(/0+$/, ''); // Elimina ceros finales
            return parts.join(','); // Une la parte entera con la decimal usando una coma
        }

        return parts.join(',');
    }

    function montoRetenido(amount, isChecked){
        // Obtén el valor actual de 'total_amount' y conviértelo a número
        var total_amount = parseFloat(document.getElementById('total_amount').value) || 0;

        // Si el checkbox está marcado, suma el monto; si no, réstalo
        if (isChecked) {
            total_amount += parseFloat(amount);
        } else {
            total_amount -= parseFloat(amount);
        }

        if (total_amount > 0){
            var total_amountFormatted = formatFloat(total_amount);
        } else {
            var total_amountFormatted = '0,00';
        }

        // Actualiza el valor de 'total_charge' con el nuevo monto y agrega la cadena '$ USD'
        document.getElementById('total_charge').value = total_amountFormatted;
        document.getElementById('total_amount').value = total_amount;
    }

    function validar() {

        $('#view').on('submit', function(e) {
            e.preventDefault(); // Evitar el envío predeterminado del formulario

            // Crea un array para almacenar los IDs de los checkboxes seleccionados
            var selected = [];

            // Obtén la instancia de DataTables
            if ($('#datatable3').css('display') === 'block') {
                var table = $('#tuser').DataTable();

                // Recorre todas las filas (no solo las visibles)
                table.rows().every(function() {
                    var row = this.node();
                    var checkbox = $(row).find('input[type="checkbox"]');

                    if (checkbox.is(':checked')) {
                        selected.push(checkbox.val()); // Agrega el valor del checkbox seleccionado
                    }
                });
            } else {
                // Recorre todas las filas de la tabla
                $('#tbank4 tbody tr').each(function() {
                    var checkbox = $(this).find('input[type="checkbox"]');
                    if (checkbox.is(':checked')) {
                        selected.push(checkbox.val()); // Agrega el valor del checkbox seleccionado
                    }
                });
            }

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
            var xtotal_amount = parseFloat(document.getElementById("total_amount").value);
            if (isNaN(xtotal_amount) || xtotal_amount <= 0){
                xseguir = false;
                document.getElementById("total_charge_error").style.display = "block";
            }
            var xcanawilbank_id = document.getElementById("canawilbank_id").value;
            if (xcanawilbank_id.length < 1){
                xseguir = false;
                document.getElementById("canawilbank_id_error").style.display = "block";
            }
            var xlinkaddress_i = document.getElementById("linkaddress_i").value;
            if (xlinkaddress_i.length < 1){
                xseguir = false;
                document.getElementById("transaction_error").style.display = "block";
            }
            if (xseguir){
                // Ahora envía el formulario
                this.submit();
            }
        });
    }
</script>
@stop
