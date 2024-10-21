@extends('adminlte::page')

@section('title', 'Transacciones')

@section('content_header')
    <h1 class="m-0 text-primary text-center"><b>Capture (Print Screen) de la Transacción Bancaria hecha</b></h1>
@stop

@section('content')
<form action="/transaction" method="POST" id="view" name="view" enctype="multipart/form-data">
    @csrf
    <input type="hidden" id="transaction_id" name="transaction_id" value="{{$transaction_id}}">
    <input type="hidden" id="orientation" name="orientation" value="">
    <input type="hidden" id="credit" name="credit" value="{{$credit}}">
    <input type="hidden" id="creditcash" name="creditcash" value="{{$creditcash}}">
    <input type="hidden" id="toaction" name="toaction" value="">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header"><i class="fas fa-desktop"></i>
                    @if ($credit == 'Y' && $creditcash == 'N')
                        <b> Operación a Crédito, no es necesaria la imagén de la transacción bancaria</b>
                    @else
                        <b> Agregar capture de pantalla de la transacción bancaria hecha</b>
                    @endif
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label class="control-label">
                            Imágen a Mostrar:
                        </label>
                        <div class="panel-body text-center" id='imgfoto'>
                            <div class="row">
                                <div class="col-md-2 form-group">
                                </div>
                                <div class="col-md-8 form-group">
                                    <img width='600' height='350' alt="" id="imagen"
                                        style="max-width: 100%; max-height: 100%;" src="/images/capture.png" />
                                </div>
                            </div>
                        </div>
                        <label class="control-label">
                            Nombre del Archivo:
                        </label>
                        <input readonly class="form-control" type="text" id="linkaddress_i"
                            name="linkaddress_i" value="" maxlength=250>
                        <div id="transaction_error" class="talert" style='display: none;'>
                            <p class="text-danger">Debe escoger una imagen para guardarla con la transacción</p>
                        </div>
                        <div id="transaction2_error" class="talert" style='display: none;'>
                            <p class="text-danger">Esta operación debe hacerla de contado porque sobrepasa su límite de crédito. Escoja una imagen para guardarla con la transacción</p>
                        </div>
                    </div>
                    <div class='col-lg-5 form-group'>
                        <label class="control-label">Buscar la imágen en su Computadora</label>
                        <div class='btn btn-grey btn-file'>
                            <i class='fas fa-folder-open'></i> Seleccionar archivo
                            <input id='fileInput' name='fileInput' type='file' accept="image/*"
                                class='file-input' onchange='mostrarImagen()' onclick='quitaMensaje()'>
                            <div class="col-md-4" id='imagengif' style='display: none;'>
                                <img src="/loading.gif" border='0' /> Subiendo Archivo al Servidor, Espere un
                                momento...
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
    function quitaMensaje() {
        $(".talert").css("display", "none");
    }

    function mostrarImagen() {
        var input = document.getElementById('fileInput');
        var imagen = document.getElementById('imagen');
        var rutaInput = document.getElementById('linkaddress_i');

        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
                var img = new Image();
                img.src = e.target.result;

                img.onload = function() {
                    // Obtener las dimensiones de la imagen
                    var width = img.width;
                    var height = img.height;

                    // Calcular las relaciones de aspecto
                    var aspectRatioWidth = width / height;
                    var aspectRatioHeight = height / width;

                    // Determinar la orientación
                    var orientation;
                    if (Math.abs(aspectRatioWidth - aspectRatioHeight) < 0.3) {
                        orientation = 'CUA';
                    } else if (aspectRatioWidth > aspectRatioHeight) {
                        orientation = 'HOR';
                    } else {
                        orientation = 'VER';
                    }

                    // Asignar la orientación al campo oculto
                    document.getElementById("orientation").value = orientation;

                    // Asignar la imagen y los estilos según la orientación
                    imagen.src = e.target.result;

                    switch (orientation){
                        case 'VER':
                            imagen.style.width = "350px";
                            imagen.style.height = "600px";
                            break;
                        case 'CUA':
                            imagen.style.width = "400px";
                            imagen.style.height = "400px";
                            break;
                        case 'HOR':
                            imagen.style.width = "600px";
                            imagen.style.height = "300px";
                            break;
                    }

                    var nombreArchivo = input.files[0].name;
                    rutaInput.value = nombreArchivo;
                };
            };

            reader.readAsDataURL(input.files[0]);
        }
    }


    function validar(){
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
                document.getElementById("toaction").value = 'photo';
            } else {
                document.getElementById("toaction").value = 'creditphoto';
            }
            document.view.submit();
        }
    }
</script>
@stop
