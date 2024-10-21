@extends('adminlte::page')

@section('title', 'Transacciones')

@section('content_header')
    <h1 class="m-0 text-primary text-center"><b>Capture (Print Screen) de la Transacción Bancaria hecha</b></h1>
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
</style>
<form action="/transaction" method="POST" id="view" name="view" enctype="multipart/form-data">
    @csrf
    <input type="hidden" id="transaction_id" name="transaction_id" value="{{$transaction_id}}">
    <input type="hidden" id="orientation" name="orientation" value="">
    <input type="hidden" id="credit" name="credit" value="{{$credit}}">
    <input type="hidden" id="creditcash" name="creditcash" value="{{$creditcash}}">
    <input type="hidden" id="imageData" name="imageData">
    <input type="hidden" id="toaction" name="toaction" value="">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header"><i class="fas fa-desktop"></i>
                    @if ($credit == 'Y' && $creditcash == 'N')
                        <b> Operación a Crédito, no es necesaria la imagén de la transacción bancaria</b>
                    @else
                        <b> Agregar capture de pantalla de la transacción bancaria hecha (Win + Shift + S)</b>
                    @endif
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-2 form-group">
                            </div>
                            <div class="col-md-8 form-group">
                                <!-- Div donde se pegará la imagen -->
                                <div id="pasteArea">
                                    @if ($credit == 'Y' && $creditcash == 'N')
                                        Operación a Crédito, no es necesaria la imagén de la transacción bancaria
                                    @else
                                        Pega aquí tu imagen (Ctrl + V)
                                    @endif
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
                                    <p class="text-danger">Debe hacer un capture para guardarla con la transacción</p>
                                </div>
                                <div id="transaction2_error" class="talert" style='display: none;'>
                                    <p class="text-danger">Esta operación debe hacerla de contado porque sobrepasa su límite de crédito. Haga un capture para guardarla con la transacción</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        @if ($credit == 'Y' && $creditcash == 'N')
                            <div class="col-md-2 form-group text-center">
                                <button type="button" id="buttongrabar" onclick="validar(2)" class="btn btn-secondary btn-block">Seguir  <i class="fa fa-arrow-circle-left"></i></button>
                            </div>
                        @else
                            <div class="col-md-2 form-group text-center">
                                <button type="button" id="buttongrabar" onclick="validar(1)" class="btn btn-success btn-block">Guardar  <i class="fa fa-save"></i></button>
                            </div>
                        @endif
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
    function quitaMensaje() {
        $(".talert").css("display", "none");
    }

    function validar(xcase){
        var xseguir = true;
        var xcredit = document.getElementById("credit").value;
        var xcreditcash = document.getElementById("creditcash").value;
        var xlinkaddress_i = document.getElementById("linkaddress_i").value;
        if (xlinkaddress_i.length < 1 && xcredit == 'N'){
            xseguir = false;
            document.getElementById("transaction_error").style.display = "block";
        } else {
            if (xlinkaddress_i.length < 1 && xcreditcash == 'Y'){
                xseguir = false;
                document.getElementById("transaction2_error").style.display = "block";
            }
        }
        if (xseguir){
            if (xcase == 1){
                document.getElementById("toaction").value = 'photoweb';
            } else {
                document.getElementById("toaction").value = 'creditphotoweb';
            }
            document.view.submit();
        }
    }
</script>
@stop
