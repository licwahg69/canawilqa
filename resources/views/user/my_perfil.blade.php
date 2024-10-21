@extends('adminlte::page')

@section('title', 'Editar mi perfil')

@section('content_header')
    <h1 class="m-0 text-primary text-center"><b>Editar mi perfil: </b><label class="text-dark">{{$users->name}}</label></h1>
@stop

@section('css')
<style type="text/css">
    .containerimghp {
        width: 210px;
        height: 200px;
    }

    .containerimghp img {
        width: 100%;
        height: 100%;
    }

    .file-select {
        position: relative;
        display: inline-block;
    }

    .file-select::before {
        background-color: #5678EF;
        color: white;
        display: flex;
        justify-content: center;
        align-items: center;
        border-radius: 3px;
        content: "Subir Foto";
        position: absolute;
        cursor: pointer;
        left: 0;
        right: 0;
        top: 0;
        bottom: 0;
    }

    .file-select input[type="file"] {
        opacity: 0;
        width: 250px;
        height: 50px;
        display: inline-block;
    }
</style>
@stop

@section('content')
@php
    $onlycellphone = str_replace($users->phone_code, '', $users->cellphone)
@endphp
<form action="/user" method="POST" id="view" name="view" enctype="multipart/form-data">
    @csrf
    <input type="hidden" id="user_id" name="user_id" value="{{$users->id}}">
    <input type="hidden" id="totalphone" name="totalphone" value="{{$users->cellphone}}">
    <input type="hidden" id="photo_path" name="photo_path" value="{{ $users->profile_photo_path }}">
    <input type="hidden" id="toaction" name="toaction" value="update">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header"><i class="fa fa-id-card"></i>
                    <b> Datos del Perfil</b>
                </div>
                <div class="card-body">
                    <div class='row'>
                        <div class="col-md-12 form-group">
                            <div class="card" style='max-width:250px; max-height=240px;'>
                                <div class="card-header" style='max-width:250px; max-height=240px;'>
                                    <i class="fa fa-image"></i>
                                    <b>Foto</b>
                                </div>
                                <div class="card-body" style="width: 250px; height: 240px;">
                                    <div class="containerimghp" id="imgfoto" name="imgfoto">
                                        @if ($users->profile_photo_path == '')
                                            @if ($users->gender == 'MAS')
                                                <img style="width: 210px; height: 200px;" id="partnerfoto" src="/avatar_sinfotom.png" />
                                            @else
                                                <img style="width: 210px; height: 200px;" id="partnerfoto" src="/avatar_sinfotof.png" />
                                            @endif
                                        @else
                                            <img style="width: 210px; height: 200px;" id="partnerfoto" src="{{ $users->profile_photo_path }}" />
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="file-select">
                                <input type="file" id="attachment" name="files1" aria-label="Archivo"
                                    accept="image/*" onchange="validar('foto')" onclick="quitaMensaje()">
                            </div>
                            <div id="files1_error" class="talert" style='display: none;'>
                                <p class="text-danger">Debe ser un formato gráfico (jpg, png, bmp)</p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label for="country_id" class="form-label"><b>País (*):</b></label>
                            <select id="country_id" name="country_id" class="form-control" onchange="getCode(this.value),cargaState(this.value),cargaDoc(this.value)" onclick="quitaMensaje()"  autofocus>
                                @foreach ($countries as $country)
                                    @if ($country->id == $users->country_id)
                                        <option value="{{$country->id}}" selected>{{$country->countryname}}</option>
                                    @else
                                        <option value="{{$country->id}}">{{$country->countryname}}</option>
                                    @endif
                                @endforeach
                            </select>
                            <div id="country_id_error" class="talert" style='display: none;'>
                                <p class="text-danger">El País es requerido</p>
                            </div>
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="location_id" class="form-label"><b>Estado o Departamento (*):</b></label>
                            <select id="location_id" name="location_id" class="form-control" onchange="cargaTown(this.value)" onclick="quitaMensaje()">
                                @foreach ($locations as $location)
                                    @if ($location->id == $users->location_id)
                                        <option value="{{$location->id}}" selected>{{$location->name_location}}</option>
                                    @else
                                        <option value="{{$location->id}}">{{$location->name_location}}</option>
                                    @endif
                                @endforeach
                            </select>
                            <div id="location_id_error" class="talert" style='display: none;'>
                                <p class="text-danger">El Estado o Departamento es requerido</p>
                            </div>
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="town_id" class="form-label"><b>Ciudad o Municipio (*):</b></label>
                            <select id="town_id" name="town_id" class="form-control" onchange="quitaMensaje()">
                                @foreach ($towns as $town)
                                    @if ($town->id == $users->town_id)
                                        <option value="{{$town->id}}" selected>{{$town->name_town}}</option>
                                    @else
                                        <option value="{{$town->id}}">{{$town->name_town}}</option>
                                    @endif
                                @endforeach
                            </select>
                            <div id="town_id_error" class="talert" style='display: none;'>
                                <p class="text-danger">La Ciudad o Municipio es requerida</p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 form-group">
                            <label for="address" class="form-label"><b>Dirección (*):</b></label>
                            <input class="form-control height" type="text" id="address" name="address" placeholder="Dirección" onkeypress="quitaMensaje()"
                                maxlength=300 value="{{ old('address') ?? $users->address ?? old('address') }}">
                        </div>
                        <div id="address_error" class="talert" style='display: none;'>
                            <p class="text-danger">La Dirección es requerida</p>
                        </div>
                    </div>
                    <div class='row'>
                        @if ($users->role == 'ALI')
                            <div class="col-md-6 form-group">
                                <label for="name">Nombre Completo (*):</label>
                                <input type="text" class="form-control" id="name" name="name" onkeypress="quitaMensaje()" maxlength="191" value="{{ old('name') ?? $users->name ?? old('name') }}" placeholder="Nombre del Usuario">
                                <div id="name_error" class="talert" style='display: none;'>
                                    <p class="text-danger">El Nombre del usuario es requerido</p>
                                </div>
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="comercial_name">Nombre Comercial (*):</label>
                                <input type="text" class="form-control" id="comercial_name" name="comercial_name" onkeypress="quitaMensaje()" maxlength="255" value="{{ old('comercial_name') ?? $users->comercial_name ?? old('comercial_name') }}" placeholder="Nombre Comercial">
                                <div id="comercial_name_error" class="talert" style='display: none;'>
                                    <p class="text-danger">El Nombre Comercial del usuario es requerido</p>
                                </div>
                            </div>
                        @else
                            <div class="col-md-12 form-group">
                                <label for="name">Nombre Completo (*):</label>
                                <input type="text" class="form-control" id="name" name="name" onkeypress="quitaMensaje()" maxlength="191" value="{{ old('name') ?? $users->name ?? old('name') }}" placeholder="Nombre del Usuario">
                                <div id="name_error" class="talert" style='display: none;'>
                                    <p class="text-danger">El Nombre del usuario es requerido</p>
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label for="email" class="form-label">Email (*):</label>
                            <input class="form-control" type="email" id="email" name="email" maxlength=191 onkeydown="quitaMensaje()" onKeyPress="solocorreos(event)" onblur="validacorreos(this.id,this.value)" value="{{ old('email') ?? $users->email ?? old('email') }}" placeholder="Email del usuario">
                            <div id="email_error" class="talert" style='display: none;'>
                                <p class="text-danger">Por favor indique una dirección de correo válida</p>
                            </div>
                            <div id="email_empty" class="talert" style='display: none;'>
                                <p class="text-danger">Debe introducir una dirección de correo válida</p>
                            </div>
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="cellphone" class="form-label"><b>Celular (*):</b></label>
                            <div class="input-group mb-3">
                                <span class="input-group-text height"><i class="bi bi-whatsapp"></i></span>
                                <span class="input-group-text height" id="spancodigo">{{ $users->phone_code }}</span>
                                <input class="form-control" type="text" id="cellphone" name="cellphone" placeholder="Número Celular" maxlength=15 onkeydown="quitaMensaje()" value="{{$onlycellphone}}" onKeyPress="solonumeros(event)">
                            </div>
                            <div id="cellphone_empty" class="talert" style='display: none;'>
                                <p class="text-danger">El Número Celular es requerido</p>
                            </div>
                            <div id="cellphone_error" class="talert" style='display: none;'>
                                <p class="text-danger">El primer número no puede ser 0</p>
                            </div>
                        </div>
                        <input type="hidden" id="phonecode" name="phonecode" value="{{$users->phone_code}}">
                        <div class="col-md-2 form-group">
                            <label for="gender" class="form-label"><b>Sexo (*):</b> </label>
                            <select id="gender" name="gender" class="select2 form-control" onchange="quitaMensaje(),cambiaAvatar(this.value)">
                                @if ($users->gender == 'MAS')
                                    <option value="MAS" selected>Masculino</option>
                                    <option value="FEM">Femenino</option>
                                @else
                                    <option value="MAS">Masculino</option>
                                    <option value="FEM" selected>Femenino</option>
                                @endif
                            </select>
                            <div id="gender_error" class="talert" style='display: none;'>
                                <p class="text-danger">El sexo del usuario es requerido</p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label for="typedoc_id" class="form-label"><b>Tipo de Documento (*):</b></label>
                            <select id="typedoc_id" name="typedoc_id" class="form-control" onchange="quitaMensaje()">
                                @foreach ($type_docs as $type_doc)
                                    @if ($type_doc->id == $users->typedoc_id)
                                        <option value="{{$type_doc->id}}" selected>{{$type_doc->description}}</option>
                                    @else
                                        <option value="{{$type_doc->id}}">{{$type_doc->description}}</option>
                                    @endif
                                @endforeach
                            </select>
                            <div id="typedoc_id_error" class="talert" style='display: none;'>
                                <p class="text-danger">El Tipo de Documento es requerido</p>
                            </div>
                        </div>
                        <div class="col-md-3 form-group">
                            <label for="numdoc" class="form-label"><b>Número Documento ID (*):</b></label>
                            <input class="form-control height" type="text" id="numdoc" name="numdoc" placeholder="Número Documento ID" onkeypress="quitaMensaje()"
                                maxlength=15 value="{{ old('numdoc') ?? $users->numdoc ?? old('numdoc') }}">
                            <div id="numdoc_error" class="talert" style='display: none;'>
                                <p class="text-danger">El Número Documento ID es requerido</p>
                            </div>
                        </div>
                        <div class="col-md-5 form-group">
                            <label for="role_name" class="form-label"><b>Rol en el Sistema:</b></label>
                            <input disabled class="form-control height" type="text" id="role_name" name="role_name"
                                maxlength=15 value="{{ old('role_name') ?? $users->role_name ?? old('role_name') }}">
                        </div>
                    </div>
                    <div class="row">
                        <p><b>A continuación, debes escribir tres preguntas de seguridad y sus respuestas, si olvidas tu contraseña, sabremos que eres tú quien desea cambiarla.</b></p>
                        <div class="col-md-6 form-group">
                            <label for="question1" class="form-label"><b>Pregunta de Seguridad 1 (*):</b> </label>
                            <input class="form-control height" type="text" id="question1" name="question1" placeholder="Pregunta de Seguridad 1"
                                maxlength=191 value="{{ old('question1') ?? $users->question1 ?? old('question1') }}" onkeypress="quitaMensaje()">
                            <div id="question1_error" class="talert" style='display: none;'>
                                <p class="text-danger">La pregunta 1 de Seguridad es requerida</p>
                            </div>
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="answer1" class="form-label"><b>Respuesta de la pregunta de Seguridad 1 (*):</b> </label>
                            <input class="form-control height" type="text" id="answer1" name="answer1" placeholder="Respuesta de la pregunta de Seguridad 1"
                                maxlength=191 value="{{ old('answer1') ?? $users->answer1 ?? old('answer1') }}" onkeypress="quitaMensaje()">
                            <div id="answer1_error" class="talert" style='display: none;'>
                                <p class="text-danger">La Respuesta a la pregunta 1 de Seguridad es requerida</p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label for="question2" class="form-label"><b>Pregunta de Seguridad 2 (*):</b> </label>
                            <input class="form-control height" type="text" id="question2" name="question2" placeholder="Pregunta de Seguridad 2"
                                maxlength=191 value="{{ old('question2') ?? $users->question2 ?? old('question2') }}" onkeypress="quitaMensaje()">
                            <div id="question2_error" class="talert" style='display: none;'>
                                <p class="text-danger">La pregunta 2 de Seguridad es requerida</p>
                            </div>
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="answer2" class="form-label"><b>Respuesta de la pregunta de Seguridad 2 (*):</b> </label>
                            <input class="form-control height" type="text" id="answer2" name="answer2" placeholder="Respuesta de la pregunta de Seguridad 2"
                                maxlength=191 value="{{ old('answer2') ?? $users->answer2 ?? old('answer2') }}" onkeypress="quitaMensaje()">
                            <div id="answer2_error" class="talert" style='display: none;'>
                                <p class="text-danger">La Respuesta a la pregunta 2 de Seguridad es requerida</p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label for="question3" class="form-label"><b>Pregunta de Seguridad 3 (*):</b> </label>
                            <input class="form-control height" type="text" id="question3" name="question3" placeholder="Pregunta de Seguridad 3"
                                maxlength=191 value="{{ old('question3') ?? $users->question3 ?? old('question3') }}" onkeypress="quitaMensaje()">
                            <div id="question3_error" class="talert" style='display: none;'>
                                <p class="text-danger">La pregunta 3 de Seguridad es requerida</p>
                            </div>
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="answer3" class="form-label"><b>Respuesta de la pregunta de Seguridad 3 (*):</b> </label>
                            <input class="form-control height" type="text" id="answer3" name="answer3" placeholder="Respuesta de la pregunta de Seguridad 3"
                                maxlength=191 value="{{ old('answer3') ?? $users->answer3 ?? old('answer3') }}" onkeypress="quitaMensaje()">
                            <div id="answer3_error" class="talert" style='display: none;'>
                                <p class="text-danger">La Respuesta a la pregunta 3 de Seguridad es requerida</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 form-group">
                        <b>(*) Campos obligatorios</b>
                    </div>
                    <div class="row">
                        <div class="col-md-2 form-group text-center">
                            <button type="button" onclick="validar('update')" class="btn btn-success btn-block">Guardar  <i class="fa fa-save"></i></button>
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
<div id="copyrigth" class="float-right d-sm-inline">
    <label class="text-primary">© {{ date_format(date_create(date("Y")),"Y") }} Cambios CANAWIL</label>, todos los derechos reservados.
</div>
@stop

@section('js')
<script>
    function cambiaAvatar(xid) {
        var xphoto_path = document.getElementById('photo_path').value;
        if  (xphoto_path.length < 1){
            var nuevaruta = "";
            if (xid == 'MAS'){
                nuevaruta = '/avatar_sinfotom.png';
            } else {
                nuevaruta = '/avatar_sinfotof.png';
            }
            $('#partnerfoto').attr('src', nuevaruta);
        }
    }

    function getCode(xxid) {
        fetch(`/code/${xxid}`)
            .then(response => response.json())
            .then(jsondata => showCode(jsondata))
    }

    function showCode(jsondata){
        let phonecode = jsondata.phonecode;

        document.getElementById("phonecode").value = phonecode;
        $('#spancodigo').text(phonecode);
    }

    function cargaState(countryId) {
        let selectLocation = document.getElementById('location_id');
        selectLocation.innerHTML = ''; // Limpiar contenido anterior
        let selectTown = document.getElementById('town_id');
        selectTown.innerHTML = ''; // Limpiar contenido anterior

        // Crear y añadir una opción por defecto
        let defaultOption = document.createElement('option');
        defaultOption.value = '';
        defaultOption.text = 'Seleccionar';
        selectLocation.appendChild(defaultOption);

        let defaultOption2 = document.createElement('option');
        defaultOption2.value = '';
        defaultOption2.text = 'Seleccionar';
        selectTown.appendChild(defaultOption2);

        fetch(`/location/${countryId}`)
        .then(response => response.json())
        .then(jsondata => {
            buildStates(jsondata);
        })
        .catch(error => {
            console.error('Error fetching locations:', error);
        });
    }

    function buildStates(states) {
        let selectLocation = document.getElementById('location_id');

        // Recorrer el array de estados y crear opciones
        states.forEach(state => {
            let option = document.createElement('option');
            option.value = state.id;  // Asumiendo que el id está en la propiedad 'id'
            option.text = state.name_location;  // Asumiendo que el nombre del estado está en la propiedad 'name'
            selectLocation.appendChild(option);
        });
    }

    function cargaTown(locationId) {
        let selectTown = document.getElementById('town_id');
        selectTown.innerHTML = ''; // Limpiar contenido anterior

        // Crear y añadir una opción por defecto
        let defaultOption2 = document.createElement('option');
        defaultOption2.value = '';
        defaultOption2.text = 'Seleccionar';
        selectTown.appendChild(defaultOption2);

        fetch(`/town/${locationId}`)
        .then(response => response.json())
        .then(jsondata => {
            buildTown(jsondata);
        })
        .catch(error => {
            console.error('Error fetching locations:', error);
        });
    }

    function buildTown(towns) {
        let selectTown = document.getElementById('town_id');

        // Recorrer el array de estados y crear opciones
        towns.forEach(town => {
            let option = document.createElement('option');
            option.value = town.id;  // Asumiendo que el id está en la propiedad 'id'
            option.text = town.name_town;  // Asumiendo que el nombre del estado está en la propiedad 'name'
            selectTown.appendChild(option);
        });
    }

    function cargaDoc(countryId) {
        let selectDoc = document.getElementById('typedoc_id');
        selectDoc.innerHTML = ''; // Limpiar contenido anterior

        // Crear y añadir una opción por defecto
        let defaultOption2 = document.createElement('option');
        defaultOption2.value = '';
        defaultOption2.text = 'Seleccionar';
        selectDoc.appendChild(defaultOption2);

        fetch(`/doc/${countryId}`)
        .then(response => response.json())
        .then(jsondata => {
            buildDoc(jsondata);
        })
        .catch(error => {
            console.error('Error fetching locations:', error);
        });
    }

    function buildDoc(typedocs) {
        let selectDoc = document.getElementById('typedoc_id');

        // Recorrer el array de estados y crear opciones
        typedocs.forEach(typedoc => {
            let option = document.createElement('option');
            option.value = typedoc.id;  // Asumiendo que el id está en la propiedad 'id'
            option.text = typedoc.description;  // Asumiendo que el nombre del estado está en la propiedad 'name'
            selectDoc.appendChild(option);
        });
    }

    function quitaMensaje(){
        $(".talert").css("display", "none");
    }

    function solonumeros(event){
        key = event.keyCode || event.which;
        tecla = String.fromCharCode(key).toLowerCase();

        letras = "1234567890";

        especiales = [8,13,37,39,46,116];
        tecla_especial = false;
        for(var i in especiales){
           if(key == especiales[i]){
              tecla_especial = true;
              break;
           }
        }

        if(letras.indexOf(tecla) ==-1 && (tecla_especial == false)){
           event.preventDefault();
        }
    }

    function solocorreos(event){
        key = event.keyCode || event.which;
        tecla = String.fromCharCode(key).toLowerCase();

        letras = " áéíóúabcdefghijklmnñopqrstuvwxyz1234567890-_.@";

        especiales = [8,37,39,46,116];
        tecla_especial = false;
        for(var i in especiales){
            if(key == especiales[i]){
                tecla_especial = true;
                break;
                }
            }

        if(letras.indexOf(tecla) ==-1 && (tecla_especial == false)){

            event.preventDefault();
        }
    }

    function validacorreos(xid,xvalor){
        var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        if (xvalor.length > 0 && !re.test(xvalor)){
            document.getElementById(xid).value = "";
            document.getElementById(xid+"_error").style.display = "block";
            document.getElementById(xid).focus();
        }
    }

    function validar(xaccion){
        var xseguir = true;
        if (xaccion == "foto"){
            var xid = document.getElementById('user_id').value;
            document.getElementById("toaction").value = xaccion;

            var xattachment1 = document.getElementById('attachment').files[0].name;
            let extension = xattachment1.split('.').pop().toLowerCase();
            xnamefile = "user_" + xid + "_foto." + extension;

            document.getElementById("photo_path").value = xnamefile;
            document.view.submit();
        } else {
            var xcountry_id = document.getElementById("country_id").value;
            if  (xcountry_id.length < 1){
                xseguir = false;
                document.getElementById("country_id_error").style.display = "block";
            }
            var xlocation_id = document.getElementById("location_id").value;
            if  (xlocation_id.length < 1){
                xseguir = false;
                document.getElementById("location_id_error").style.display = "block";
            }
            var xtown_id = document.getElementById("town_id").value;
            if  (xtown_id.length < 1){
                xseguir = false;
                document.getElementById("town_id_error").style.display = "block";
            }
            var xaddress = document.getElementById("address").value;
            if  (xaddress.length < 1){
                xseguir = false;
                document.getElementById("address_error").style.display = "block";
            }
            var xname = document.getElementById("name").value;
            if  (xname.length < 1){
                xseguir = false;
                document.getElementById("name_error").style.display = "block";
            }
            if (document.getElementById("comercial_name")){
                var xcomercial_name = document.getElementById("comercial_name").value;
                if  (xcomercial_name.length < 1){
                    xseguir = false;
                    document.getElementById("comercial_name_error").style.display = "block";
                }
            }
            var xemail = document.getElementById("email").value;
            if  (xemail.length < 1){
                xseguir = false;
                document.getElementById("email_error").style.display = "block";
            }
            var xphonecode = document.getElementById("phonecode").value;
            var xcellphone = document.getElementById("cellphone").value;
            var firstCharacter = xcellphone.charAt(0);
            if (xcellphone.length < 1){
                xseguir = false;
                document.getElementById("cellphone_empty").style.display = "block";
            } else {
                if (firstCharacter == "0"){
                    xseguir = false;
                    document.getElementById("cellphone").value = "";
                    document.getElementById("cellphone_error").style.display = "block";
                    document.getElementById("cellphone").focus();
                } else {
                    var totalphone = xphonecode + xcellphone;
                    document.getElementById("totalphone").value = totalphone;
                }
            }
            var xgender = document.getElementById("gender").value;
            if  (xgender.length < 1){
                xseguir = false;
                document.getElementById("gender_error").style.display = "block";
            }
            var xtypedoc_id = document.getElementById("typedoc_id").value;
            if  (xtypedoc_id.length < 1){
                xseguir = false;
                document.getElementById("typedoc_id_error").style.display = "block";
            }
            var xnumdoc = document.getElementById("numdoc").value;
            if  (xnumdoc.length < 1){
                xseguir = false;
                document.getElementById("numdoc_error").style.display = "block";
            }
            var xquestion1 = document.getElementById("question1").value;
            if  (xquestion1.length < 1){
                xseguir = false;
                document.getElementById("question1_error").style.display = "block";
            }
            var xanswer1 = document.getElementById("answer1").value;
            if  (xanswer1.length < 1){
                xseguir = false;
                document.getElementById("answer1_error").style.display = "block";
            }
            var xquestion2 = document.getElementById("question2").value;
            if  (xquestion2.length < 1){
                xseguir = false;
                document.getElementById("question2_error").style.display = "block";
            }
            var xanswer2 = document.getElementById("answer2").value;
            if  (xanswer2.length < 1){
                xseguir = false;
                document.getElementById("answer2_error").style.display = "block";
            }
            var xquestion3 = document.getElementById("question3").value;
            if  (xquestion3.length < 1){
                xseguir = false;
                document.getElementById("question3_error").style.display = "block";
            }
            var xanswer3 = document.getElementById("answer3").value;
            if  (xanswer3.length < 1){
                xseguir = false;
                document.getElementById("answer3_error").style.display = "block";
            }
        }
        if (xseguir){
            document.view.submit();
        }
    }
</script>
@stop
