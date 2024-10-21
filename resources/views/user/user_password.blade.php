<!doctype html>
<html lang="es">
    <head>
        <!-- Required meta tags -->
        <title>CANAWIL | Password de Usuario</title>
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
                    <h4 style="color: #0b0611"><label class="text-start">Cambios CANAWIL</label></h4>
                </a>
            </div>
        </nav>

        <div class="container-fluid">
            <br><br><br><br><br><br><br>
            <div class="row">
                <form action="/passworduser" method="POST" id="view" name="view">
                    @csrf
                    <input type="hidden" id="user_id" name="user_id" value="{{$id}}">
                    <input type="hidden" id="totalphone" name="totalphone" value="">
                    <input type="hidden" id="toaction" name="toaction" value="passworduser">
                    <div class="col-12">
                        <h1 class="m-0 text-primary text-center"><b>Crear password de Usuario <label class="text-dark">({{$user_name}})</label></b></h1>
                        <br>
                        <div class="card">
                            <div class="card-header"><i class="fa fa-user-tie"></i>
                                <b> Datos del Usuario</b>
                            </div>
                            @foreach ($users as $user)
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4 form-group">
                                        <label for="country_id" class="form-label"><b>País (*):</b></label>
                                        <select id="country_id" name="country_id" class="form-select" onchange="getCode(this.value),cargaState(this.value),cargaDoc(this.value)" onclick="quitaMensaje()"  autofocus>
                                            <option value="">Seleccionar</option>
                                            @foreach ($countries as $country)
                                                <option value="{{$country->id}}">{{$country->countryname}}</option>
                                            @endforeach
                                        </select>
                                        <div id="country_id_error" class="talert" style='display: none;'>
                                            <p class="text-danger">El País es requerido</p>
                                        </div>
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <label for="location_id" class="form-label"><b>Estado o Departamento (*):</b></label>
                                        <select id="location_id" name="location_id" class="form-select" onchange="cargaTown(this.value)" onclick="quitaMensaje()">
                                            <option value="">Seleccionar</option>
                                        </select>
                                        <div id="location_id_error" class="talert" style='display: none;'>
                                            <p class="text-danger">El Estado o Departamento es requerido</p>
                                        </div>
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <label for="town_id" class="form-label"><b>Ciudad o Municipio (*):</b></label>
                                        <select id="town_id" name="town_id" class="form-select" onchange="quitaMensaje()">
                                            <option value="">Seleccionar</option>
                                        </select>
                                        <div id="town_id_error" class="talert" style='display: none;'>
                                            <p class="text-danger">La Ciudad o Municipio es requerida</p>
                                        </div>
                                    </div>
                                </div>
                                <br>
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
                                <br>
                                <div class='row'>
                                    @if ($user->role == 'ALI')
                                        <div class="col-md-6 form-group">
                                            <label for="name" class="form-label"><b>Nombre Completo:</b></label>
                                            <input disabled class="form-control height" type="text" id="name" name="name" placeholder="Nombre Completo"
                                                maxlength=191 value="{{ old('name') ?? $user->name ?? old('name') }}" onkeypress="quitaMensaje()">
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label for="comercial_name" class="form-label"><b>Nombre Comercial (*):</b></label>
                                            <input type="text" class="form-control" id="comercial_name" name="comercial_name" onkeypress="quitaMensaje()" maxlength="255" value="{{ old('comercial_name') ?? $users->comercial_name ?? old('comercial_name') }}" placeholder="Nombre Comercial">
                                            <div id="comercial_name_error" class="talert" style='display: none;'>
                                                <p class="text-danger">El Nombre Comercial del usuario es requerido</p>
                                            </div>
                                        </div>
                                    @else
                                        <div class="col-md-12 form-group">
                                            <label for="name" class="form-label"><b>Nombre Completo:</b></label>
                                            <input disabled class="form-control height" type="text" id="name" name="name" placeholder="Nombre Completo"
                                                maxlength=191 value="{{ old('name') ?? $user->name ?? old('name') }}" onkeypress="quitaMensaje()">
                                        </div>
                                    @endif
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-md-3 form-group">
                                        <label for="cellphone" class="form-label"><b>Celular (*):</b></label>
                                        <div class="input-group mb-3">
                                            <span class="input-group-text height"><i class="bi bi-whatsapp"></i></span>
                                            <span class="input-group-text height" id="spancodigo"></span>
                                            <input class="form-control" type="text" id="cellphone" name="cellphone" placeholder="Número Celular" maxlength=15 onkeydown="quitaMensaje()" value="" onKeyPress="solonumeros(event)">
                                        </div>
                                        <div id="cellphone_empty" class="talert" style='display: none;'>
                                            <p class="text-danger">El Número Celular es requerido</p>
                                        </div>
                                        <div id="cellphone_error" class="talert" style='display: none;'>
                                            <p class="text-danger">El primer número no puede ser 0</p>
                                        </div>
                                    </div>
                                    <input type="hidden" id="phonecode" name="phonecode" value="">
                                    <div class="col-md-4 form-group">
                                        <label for="email" class="form-label"><b>Email:</b></label>
                                        <input disabled class="form-control height" type="text" id="email" name="email" placeholder="Correo Electrónico"
                                            maxlength=191 value="{{$xemail}}" onkeypress="quitaMensaje()">
                                    </div>
                                    <div class="col-md-2 form-group">
                                        <label for="gender" class="form-label"><b>Sexo:</b> </label>
                                        <select disabled id="gender" name="gender" class="form-control" onchange="quitaMensaje()">
                                            @if ($user->gender == 'MAS')
                                                <option value="MAS" selected>Masculino</option>
                                                <option value="FEM">Femenino</option>
                                            @else
                                                <option value="MAS">Masculino</option>
                                                <option value="FEM" selected>Femenino</option>
                                            @endif
                                        </select>
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label for="role_name" class="form-label"><b>Rol en el Sistema:</b></label>
                                        @if ($user->role == 'ADM')
                                            <input disabled class="form-control height" type="text" id="role_name" name="role_name" placeholder="Correo Electrónico"
                                                maxlength=191 value="Administrador del Sistema" onkeypress="quitaMensaje()">
                                        @endif
                                        @if ($user->role == 'ALI')
                                            <input disabled class="form-control height" type="text" id="role_name" name="role_name" placeholder="Correo Electrónico"
                                                maxlength=191 value="Aliado Comercial" onkeypress="quitaMensaje()">
                                        @endif
                                        @if ($user->role == 'USU')
                                            <input disabled class="form-control height" type="text" id="role_name" name="role_name" placeholder="Correo Electrónico"
                                                maxlength=191 value="Usuario" onkeypress="quitaMensaje()">
                                        @endif
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-md-3 form-group">
                                        <label for="typedoc_id" class="form-label"><b>Tipo de Documento (*):</b></label>
                                        <select id="typedoc_id" name="typedoc_id" class="form-select" onchange="quitaMensaje()">
                                            <option value="">Seleccionar</option>
                                        </select>
                                        <div id="typedoc_id_error" class="talert" style='display: none;'>
                                            <p class="text-danger">El Tipo de Documento es requerido</p>
                                        </div>
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label for="numdoc" class="form-label"><b>Número Documento ID (*):</b></label>
                                        <input class="form-control height" type="text" id="numdoc" name="numdoc" placeholder="Número Documento ID" onkeypress="quitaMensaje()"
                                            maxlength=15 value="{{ old('numdoc') ?? $user->numdoc ?? old('numdoc') }}">
                                        <div id="numdoc_error" class="talert" style='display: none;'>
                                            <p class="text-danger">El Número Documento ID es requerido</p>
                                        </div>
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label for="password" class="form-label"><b>Contraseña (*):</b></label>
                                        <input class="form-control height" type="password" id="password" name="password" placeholder="Contraseña del Usuario"
                                            maxlength=191 value="" onkeypress="quitaMensaje()">
                                        <div id="password_error" class="talert" style='display: none;'>
                                            <p class="text-danger">La contraseña es requerida</p>
                                        </div>
                                        <div id="pass_error" class="talert" style='display: none;'>
                                            <p class="text-danger">Las contraseñas no coinciden</p>
                                        </div>
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label for="repassword" class="form-label"><b>Repita la Contraseña (*):</b></label>
                                        <input class="form-control height" type="password" id="repassword" name="repassword" placeholder="Repetir Contraseña"
                                            maxlength=191 value="" onkeypress="quitaMensaje()">
                                        <div id="repassword_error" class="talert" style='display: none;'>
                                            <p class="text-danger">Debe repetir la contraseña</p>
                                        </div>
                                    </div>

                                </div>
                                <br>
                                <div class="row">
                                    <p><b>A continuación, debes escribir tres preguntas de seguridad y sus respuestas, en caso que olvides tu contraseña, sabremos que eres tú quien desea cambiarla.</b></p>
                                    <div class="col-md-6 form-group">
                                        <label for="question1" class="form-label"><b>Pregunta de Seguridad 1 (*):</b> </label>
                                        <input class="form-control height" type="text" id="question1" name="question1" placeholder="Pregunta de Seguridad 1"
                                            maxlength=191 value="{{ old('question1') }}" onkeypress="quitaMensaje()">
                                        <div id="question1_error" class="talert" style='display: none;'>
                                            <p class="text-danger">La pregunta 1 de Seguridad es requerida</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label for="answer1" class="form-label"><b>Respuesta de la pregunta de Seguridad 1 (*):</b> </label>
                                        <input class="form-control height" type="text" id="answer1" name="answer1" placeholder="Respuesta de la pregunta de Seguridad 1"
                                            maxlength=191 value="{{ old('answer1') }}" onkeypress="quitaMensaje()">
                                        <div id="answer1_error" class="talert" style='display: none;'>
                                            <p class="text-danger">La Respuesta a la pregunta 1 de Seguridad es requerida</p>
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-md-6 form-group">
                                        <label for="question2" class="form-label"><b>Pregunta de Seguridad 2 (*):</b> </label>
                                        <input class="form-control height" type="text" id="question2" name="question2" placeholder="Pregunta de Seguridad 2"
                                            maxlength=191 value="{{ old('question2') }}" onkeypress="quitaMensaje()">
                                        <div id="question2_error" class="talert" style='display: none;'>
                                            <p class="text-danger">La pregunta 2 de Seguridad es requerida</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label for="answer2" class="form-label"><b>Respuesta de la pregunta de Seguridad 2 (*):</b> </label>
                                        <input class="form-control height" type="text" id="answer2" name="answer2" placeholder="Respuesta de la pregunta de Seguridad 2"
                                            maxlength=191 value="{{ old('answer2') }}" onkeypress="quitaMensaje()">
                                        <div id="answer2_error" class="talert" style='display: none;'>
                                            <p class="text-danger">La Respuesta a la pregunta 2 de Seguridad es requerida</p>
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-md-6 form-group">
                                        <label for="question3" class="form-label"><b>Pregunta de Seguridad 3 (*):</b> </label>
                                        <input class="form-control height" type="text" id="question3" name="question3" placeholder="Pregunta de Seguridad 3"
                                            maxlength=191 value="{{ old('question3') }}" onkeypress="quitaMensaje()">
                                        <div id="question3_error" class="talert" style='display: none;'>
                                            <p class="text-danger">La pregunta 3 de Seguridad es requerida</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label for="answer3" class="form-label"><b>Respuesta de la pregunta de Seguridad 3 (*):</b> </label>
                                        <input class="form-control height" type="text" id="answer3" name="answer3" placeholder="Respuesta de la pregunta de Seguridad 3"
                                            maxlength=191 value="{{ old('answer3') }}" onkeypress="quitaMensaje()">
                                        <div id="answer3_error" class="talert" style='display: none;'>
                                            <p class="text-danger">La Respuesta a la pregunta 3 de Seguridad es requerida</p>
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <div class="col-md-12 form-group">
                                    <b>(*) Campos obligatorios</b>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-md-3">
                                        <button class="btn btn-success" type="button"
                                            onclick="validar()">
                                            Guardar <i class="fa fa-arrow-circle-right"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <br><br>

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
        <script src="https://kit.fontawesome.com/2c36e9b7b1.js" crossorigin="anonymous"></script>

        <script>
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

            function getCode(xxid) {
                fetch(`/code2/${xxid}`)
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

                fetch(`/location2/${countryId}`)
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

                fetch(`/town2/${locationId}`)
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

                fetch(`/doc2/${countryId}`)
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

            function validar(){
                var xseguir = true;
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
                if (document.getElementById("comercial_name")){
                var xcomercial_name = document.getElementById("comercial_name").value;
                    if  (xcomercial_name.length < 1){
                        xseguir = false;
                        document.getElementById("comercial_name_error").style.display = "block";
                    }
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
                var xpassword = document.getElementById("password").value;
                if  (xpassword.length < 1){
                    xseguir = false;
                    document.getElementById("password_error").style.display = "block";
                }
                var xrepassword = document.getElementById("repassword").value;
                if  (xrepassword.length < 1){
                    xseguir = false;
                    document.getElementById("repassword_error").style.display = "block";
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
                if  (xpassword != xrepassword){
                    xseguir = false;
                    document.getElementById("password").value = "";
                    document.getElementById("repassword").value = "";
                    document.getElementById("pass_error").style.display = "block";
                }
                if (xseguir){
                    document.view.submit();
                }
            }
        </script>
    </body>

</html>




