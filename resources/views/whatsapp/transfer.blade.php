<!doctype html>
<html lang="es">
    <head>
        <!-- Required meta tags -->
        <title>CANAWIL | Detalle de transferencia</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">

        <link href="https://fonts.googleapis.com/css2?family=Mulish:wght@300;900&display=swap" rel="stylesheet">
        <link rel="preconnect" href="https://fonts.gstatic.com">

        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/normalize.css@8.0.1/normalize.min.css">

        <link rel="Shortcut Icon" type="image/x-icon" href="/favicon.ico" />
    </head>

    <body>
        <nav class="navbar navbar-expand-lg navbar-light fixed-top" style="background: #ffffff;">
            <div class="container-fluid">
                <a href="#">
                    <img src="/checkicon.png" alt="" width="100" height="95">
                    <h4 style="color: #0b0611"><label class="text-start">CANAWIL Cambios</label></h4>
                </a>
            </div>
        </nav>

        <div class="container-fluid">
            <br><br><br><br><br><br><br>
            <div class="row">
                <div class="col-md-1 form-group">
                </div>
                <div class="col-md-10 form-group">
                    <div class="card">
                        <div class="card-header"><i class="fas fa-cash-register"></i>
                            <b> Datos de la Transacción <label class="text-danger"> (Estatus: <i class="fas fa-traffic-light"></i> Transferido)</label></b>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-2 form-group">
                                    <label for="transaction_id" class="form-label"><b>ID Transacción:</b></label>
                                    <input disabled class="form-control height" type="text" id="transaction_id" name="transaction_id" value="{{$transfers[0]->id}}">
                                </div>
                                <div class="col-md-3 form-group">
                                    <label for="conversion_id" class="form-label"><b>Tipo de cambio:</b></label>
                                    <input disabled class="form-control height" type="text" id="conversion_id" name="conversion_id" value="{{$transfers[0]->a_to_b}}">
                                </div>
                                <div class="col-md-7 form-group">
                                    <label for="conversion_description" class="form-label"><b>Descripción:</b></label>
                                    <input disabled class="form-control height" type="text" id="conversion_description" name="conversion_description" value="{{$transfers[0]->conversion_description}}">
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-3 form-group">
                                    <label for="mount_value" id="label_mount_value"><b>Monto a cambiar:</b></label>
                                    <input disabled type="text" class="form-control text-right" id="mount_value" name="mount_value" value="{{ trim($transfers[0]->mount_value_fm) }}{{ trim($transfers[0]->symbol) }} {{ trim($transfers[0]->currency) }}">
                                </div>
                                <div class="col-md-2 form-group">
                                    <label for="conversion_value" id="label_conversion_value"><b>Tasa de cambio {{$transfers[0]->currency2}}:</b></label>
                                    <input disabled type="text" class="form-control text-right" style="color: red; background-color: white" id="conversion_value" name="conversion_value" value="{{$transfers[0]->two_decimals == 'Y' ? number_format($transfers[0]->conversion_value, 2).' '.$transfers[0]->currency : $transfers[0]->conversion_value.' '.$transfers[0]->currency}}">
                                </div>
                                <div class="col-md-3 form-group">
                                    <label for="mount_change" id="label_mount_change"><b>Monto a pagar {{$transfers[0]->currency2}}:</b></label>
                                    <input disabled type="text" class="form-control text-right" style="color:darkgreen; background-color: white" id="mount_change" name="mount_change" value="{{ trim($transfers[0]->mount_change_fm).' '.$transfers[0]->symbol2 }}">
                                </div>
                                <div class="col-md-2 form-group">
                                    <label for="reference_conversion_value" id="label_reference_conversion_value"><b>Tasa Ref. {{$transfers[0]->currency3}}:</b></label>
                                    <input disabled type="text" class="form-control text-right" style="color: red; background-color: white" id="reference_conversion_value" name="reference_conversion_value" value="{{$transfers[0]->two_decimals == 'Y' ? number_format($transfers[0]->reference_conversion_value, 2).' '.$transfers[0]->currency2 : $transfers[0]->reference_conversion_value.' '.$transfers[0]->currency2}}">
                                </div>
                                <div class="col-md-2 form-group">
                                    <label for="mount_reference" id="label_mount_reference"><b>Monto Ref. {{$transfers[0]->currency3}}:</b></label>
                                    <input disabled type="text" class="form-control text-right" style="color: darkgreen; background-color: white" id="mount_reference" name="mount_reference" value="{{ trim($transfers[0]->mount_reference_fm).' '.$transfers[0]->symbol3 }}">
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-4 form-group">
                                    <label for="doc_description" class="form-label"><b>Tipo de Documento:</b></label>
                                    <input disabled class="form-control height" type="text" id="doc_description" name="doc_description" value="{{$transfers[0]->doc_description}}">
                                </div>
                                <div class="col-md-3 form-group">
                                    <label for="numdoc" class="form-label"><b>Número Documento ID:</b></label>
                                    <input disabled class="form-control height" type="text" id="numdoc" name="numdoc" value="{{$transfers[0]->numdoc}}">
                                </div>
                                <div class="col-md-5 form-group">
                                    <label for="account_holder" class="form-label"><b>Nombre del Titular:</b></label>
                                    <input disabled class="form-control height" type="text" id="account_holder" name="account_holder" value="{{$transfers[0]->account_holder}}">
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-8 form-group">
                                    <label for="bankname" class="form-label"><b>Banco:</b></label>
                                    <input disabled class="form-control height" type="text" id="bankname" name="bankname" value="{{$transfers[0]->bankname}}">
                                </div>
                                <div class="col-md-4 form-group">
                                    <label for="account_number" class="form-label"><b>Número de cuenta:</b></label>
                                    <input disabled class="form-control height" type="text" id="account_number" name="account_number" value="{{$transfers[0]->account_number}}">
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-8 form-group">
                                    <label for="payer" class="form-label"><b>Nombre del pagador:</b></label>
                                    <input disabled class="form-control" type="text" id="payer" name="payer" value="{{$transfers[0]->payer_name}}">
                                </div>
                                <div class="col-md-4 form-group">
                                    <label for="cellphone" class="form-label"><b>Celular:</b></label>
                                    <div class="input-group mb-3">
                                        <span class="input-group-text height"><i class="bi bi-whatsapp"></i></span>
                                        <span class="input-group-text height" id="spancodigo">{{ $phone_code }}</span>
                                        <input disabled class="form-control" type="text" id="cellphone" name="cellphone" maxlength=15 value="{{$onlycellphone}}">
                                    </div>
                                </div>
                            </div>
                            <br>
                            <hr>
                            @if ($transfers[0]->role == 'ALI')
                                <h5 style="color: blue"><b>Datos de la transferencia del Aliado Comercial: <label style="color:black">{{$transfers[0]->comercial_name}}</label></b></h5>
                            @else
                                <h5 style="color: blue"><b>Datos de la transferencia del Usuario: <label style="color:black">{{$transfers[0]->comercial_name}}</label></b></h5>
                            @endif

                            <hr>
                            <br>
                            <div class="row">
                                <div class="col-md-4 form-group">
                                    <label for="canawil_bank_name" class="form-label"><b>{{ trim($transfers[0]->mount_value_fm) }}{{ trim($transfers[0]->symbol) }} {{ trim($transfers[0]->currency) }} transferidos a:</b></label>
                                    <input disabled class="form-control" type="text" id="canawil_bank_name" name="canawil_bank_name" value="{{$transfers[0]->canawil_bank_name}}">
                                </div>
                                <div class="col-md-3 form-group">
                                    <label for="canawil_account_number" class="form-label"><b>Cuenta:</b></label>
                                    <input disabled class="form-control" type="text" id="canawil_account_number" name="canawil_account_number" value="{{$transfers[0]->canawil_account_number}}">
                                </div>
                                <div class="col-md-3 form-group">
                                    <label for="waytopay_description" class="form-label"><b>Medio usado para el envío:</b></label>
                                    <input disabled class="form-control" type="text" id="waytopay_description" name="waytopay_description" value="{{$transfers[0]->waytopay_description}}">
                                </div>
                                <div class="col-md-2 form-group">
                                    <label for="waytopay_reference" class="form-label"><b>{{$transfers[0]->reference_text}}:</b></label>
                                    <input disabled class="form-control" type="text" id="waytopay_reference" name="waytopay_reference" value="{{$transfers[0]->waytopay_reference}}">
                                </div>
                            </div>
                            <br>
                            <div class="card">
                                <div class="card-header"><i class="fas fa-camera"></i>
                                    <b> Foto de la transacción bancaria</b>
                                </div>
                                <div class="card-body">
                                    <div class="panel-body text-center" id='imgfoto'>
                                        <div class="row">
                                            <div class="col-md-12 form-group text-center" id="foto_web" style='display: none;'>
                                                @switch($transfers[0]->transaction_image_orientation)
                                                    @case('VER')
                                                        <img width='350' height='600' alt="" id="imagen" style="max-width: 100%; max-height: 100%;" src="{{$transfers[0]->bank_image}}" />
                                                        @break
                                                    @case('CUA')
                                                        <img width='400' height='400' alt="" id="imagen" style="max-width: 100%; max-height: 100%;" src="{{$transfers[0]->bank_image}}" />
                                                        @break
                                                    @case('HOR')
                                                        <img width='600' height='300' alt="" id="imagen" style="max-width: 100%; max-height: 100%;" src="{{$transfers[0]->bank_image}}" />
                                                        @break
                                                @endswitch
                                            </div>
                                            <div class="col-md-12 form-group text-center" id="foto_mobile" style='display: none;'>
                                                @switch($transfers[0]->transaction_image_orientation)
                                                    @case('VER')
                                                        <img width='350' height='600' alt="" id="imagen" style="max-width: 100%; max-height: 100%;" src="{{$transfers[0]->bank_image}}" />
                                                        @break
                                                    @case('CUA')
                                                        <img width='400' height='400' alt="" id="imagen" style="max-width: 100%; max-height: 100%;" src="{{$transfers[0]->bank_image}}" />
                                                        @break
                                                    @case('HOR')
                                                        <img width='500' height='200' alt="" id="imagen" style="max-width: 100%; max-height: 100%;" src="{{$transfers[0]->bank_image}}" />
                                                        @break
                                                @endswitch
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <br>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <i class="fas fa-dollar-sign"></i> <b>Datos de la transferencia</b>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-5 form-group">
                                    <label for="canawil_bank_name" class="form-label"><b>Fondos transferidos desde el banco Canawil:</b></label>
                                    <input disabled class="form-control" type="text" id="canawil_bank_name" name="canawil_bank_name" value="{{$transfers[0]->canawil_bank_name}}">
                                </div>
                                <div class="col-md-4 form-group">
                                    <label for="transfer_waytopay_description" class="form-label"><b>Tipo de pago a usado:</b></label>
                                    <input disabled class="form-control" type="text" id="transfer_waytopay_description" name="transfer_waytopay_description" value="{{$transfers[0]->transfer_waytopay_description}}">
                                </div>
                                <div class="col-md-3 form-group">
                                    <label for="transfer_waytopay_reference" class="form-label" id="label_reference"><b>{{$transfers[0]->transfer_reference_text}}:</b></label>
                                    <input disabled class="form-control" type="text" id="transfer_waytopay_reference" name="transfer_waytopay_reference" value="{{$transfers[0]->transfer_waytopay_reference}}" placeholder="Referencia">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header"><i class="fas fa-camera"></i>
                            <b> Foto de la transferencia bancaria realizada</b>
                        </div>
                        <div class="card-body">
                            <div class="panel-body text-center" id='imgfoto'>
                                <div class="row">
                                    <div class="col-md-12 form-group text-center" id="foto_web2" style='display: none;'>
                                        @switch($transfers[0]->transfer_image_orientation)
                                            @case('VER')
                                                <img width='350' height='600' alt="" id="imagen" style="max-width: 100%; max-height: 100%;" src="{{$transfers[0]->transfer_bank_image}}" />
                                                @break
                                            @case('CUA')
                                                <img width='400' height='400' alt="" id="imagen" style="max-width: 100%; max-height: 100%;" src="{{$transfers[0]->transfer_bank_image}}" />
                                                @break
                                            @case('HOR')
                                                <img width='600' height='300' alt="" id="imagen" style="max-width: 100%; max-height: 100%;" src="{{$transfers[0]->transfer_bank_image}}" />
                                                @break
                                        @endswitch
                                    </div>
                                    <div class="col-md-12 form-group text-center" id="foto_mobile2" style='display: none;'>
                                        @switch($transfers[0]->transfer_image_orientation)
                                            @case('VER')
                                                <img width='350' height='600' alt="" id="imagen" style="max-width: 100%; max-height: 100%;" src="{{$transfers[0]->transfer_bank_image}}" />
                                                @break
                                            @case('CUA')
                                                <img width='400' height='400' alt="" id="imagen" style="max-width: 100%; max-height: 100%;" src="{{$transfers[0]->transfer_bank_image}}" />
                                                @break
                                            @case('HOR')
                                                <img width='500' height='200' alt="" id="imagen" style="max-width: 100%; max-height: 100%;" src="{{$transfers[0]->transfer_bank_image}}" />
                                                @break
                                        @endswitch
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <br><br>

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
        <script src="https://kit.fontawesome.com/2c36e9b7b1.js" crossorigin="anonymous"></script>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                if (window.innerWidth > 768) {
                    document.getElementById("foto_web").style.display = "block";
                    document.getElementById("foto_mobile").style.display = "none";
                    document.getElementById("foto_web2").style.display = "block";
                    document.getElementById("foto_mobile2").style.display = "none";
                } else {
                    document.getElementById("foto_web").style.display = "none";
                    document.getElementById("foto_mobile").style.display = "block";
                    document.getElementById("foto_web2").style.display = "none";
                    document.getElementById("foto_mobile2").style.display = "block";
                }
            });
        </script>
        <script>
            $(document).ready(function() {
                var xcuenta = document.getElementById("canawil_account_number").value;
                if (xcuenta.length > 4) {
                    // Obtener los últimos 4 dígitos
                    let ultimos4 = xcuenta.slice(-4);
                    // Reemplazar el resto con "x"
                    let enmascarado = "X".repeat(xcuenta.length - 4) + ultimos4;
                    // Mostrar el valor enmascarado en el input
                    document.getElementById("canawil_account_number").value = enmascarado;
                }

                var xcuenta2 = document.getElementById("account_number").value;
                if (xcuenta2.length > 4) {
                    // Obtener los últimos 4 dígitos
                    let xultimos4 = xcuenta2.slice(-4);
                    // Reemplazar el resto con "x"
                    let enmascarado2 = "X".repeat(xcuenta2.length - 4) + xultimos4;
                    // Mostrar el valor enmascarado en el input
                    document.getElementById("account_number").value = enmascarado2;
                }

                var xcuenta3 = document.getElementById("transfer_waytopay_reference").value;
                if (xcuenta3.length > 4) {
                    // Obtener los últimos 4 dígitos
                    let xxultimos4 = xcuenta3.slice(-4);
                    // Reemplazar el resto con "x"
                    let enmascarado3 = "X".repeat(xcuenta3.length - 4) + xxultimos4;
                    // Mostrar el valor enmascarado en el input
                    document.getElementById("transfer_waytopay_reference").value = enmascarado3;
                }

                var xcuenta4 = document.getElementById("waytopay_reference").value;
                if (xcuenta4.length > 4) {
                    // Obtener los últimos 4 dígitos
                    let xxxultimos4 = xcuenta4.slice(-4);
                    // Reemplazar el resto con "x"
                    let enmascarado4 = "X".repeat(xcuenta4.length - 4) + xxxultimos4;
                    // Mostrar el valor enmascarado en el input
                    document.getElementById("waytopay_reference").value = enmascarado4;
                }
            });
        </script>
    </body>

</html>
