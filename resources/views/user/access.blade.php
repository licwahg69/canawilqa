@extends('adminlte::page')

@section('title', 'Accesos de Usuario')

@section('content_header')
@foreach ($users as $user)
   <h1 class="m-0 text-primary text-center"><b>Administrar Accesos de {{$user->name}}</b></h1>
@endforeach
@stop

@section('content')
@php
    use Illuminate\Support\Facades\DB;

@endphp
<form action="/user" method="POST" id="view" name="view">
    @csrf
    @foreach ($users as $user)
        <input type="hidden" id="user_id" name="user_id" value="{{$user->id}}">
        <input type="hidden" id="role" name="role" value="{{$user->role}}">
        <input type="hidden" id="min" name="min" value="{{$min}}">
        <input type="hidden" id="max" name="max" value="{{$max}}">
        <input type="hidden" id="toaction" name="toaction" value="access">
        @php
            $user_id = $user->id;
        @endphp
    @endforeach

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header"><i class="fa fa-id-card"></i>
                    <b> Datos del Usuario</b>
                </div>
                <div class="card-body">
                    @foreach ($users as $user)
                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label for="name">Nombre Completo:</label>
                            <input disabled type="text" class="form-control" id="name" name="name" maxlength="191" value="{{ old('name') ?? $user->name ?? old('name') }}">
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="email">Email:</label>
                            <input disabled type="text" class="form-control" id="email" name="email" maxlength="191" value="{{ old('email') ?? $user->email ?? old('email') }}">
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="role_name">Rol en el Sistema:</label>
                            <input disabled type="text" class="form-control" id="role_name" name="role_name" maxlength="191" value="{{ old('role_name') ?? $user->role_name ?? old('role_name') }}">
                        </div>
                    </div>
                    @endforeach
                    <div class="row">
                        <div class="col-md-12 form-group">
                            <div class="card">
                                <div class="card-header">
                                    <i class="fa fa-check-double"></i><b> Menú del Sistema</b>
                                </div>
                                <div class="card-body">
                                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                                        @php
                                            $active = true;
                                        @endphp
                                        @foreach ($menus as $menu)
                                            <li class="nav-item" role="presentation">
                                                @if ($active)
                                                    @php
                                                        $active = false;
                                                    @endphp
                                                    <button class="text-success nav-link active" id="tab-{{$menu->id}}" data-bs-toggle="tab" data-bs-target="#tab-pane-{{$menu->id}}" type="button" role="tab" aria-controls="tab-pane-{{$menu->id}}" aria-selected="true"><b>{{$menu->menu_name}}</b></button>
                                                @else
                                                    <button class="text-success nav-link" id="tab-{{$menu->id}}" data-bs-toggle="tab" data-bs-target="#tab-pane-{{$menu->id}}" type="button" role="tab" aria-controls="tab-pane-{{$menu->id}}" aria-selected="false"><b>{{$menu->menu_name}}</b></button>
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>
                                    <div class="card">
                                        <div class="tab-content" id="myTabContent">
                                            @php
                                                $active = true;
                                            @endphp
                                            @foreach ($menus as $menu)
                                                @php
                                                    $sql = "select * from menu_popups where menu_id = ".$menu->id." and rowstatus = 'ACT'".' order by menuorder';
                                                    $menu_popups = DB::select($sql);
                                                @endphp
                                                @if ($active)
                                                    @php
                                                        $active = false;
                                                    @endphp
                                                    <div class="tab-pane fade show active" id="tab-pane-{{$menu->id}}" role="tabpanel" aria-labelledby="tab-{{$menu->id}}" tabindex="0">
                                                        <div class="row col-md-12 form-group">
                                                            <div class="col-md-12 form-group">
                                                                <br>
                                                                <table id="table{{$menu->id}}" class="table table-striped table-bordered">
                                                                    <thead class="bg-dark text-white">
                                                                        <th width="60" class="text-center">Habilitar</th>
                                                                        <th class="text-center">Nombre del Sub Menú</th>
                                                                        <th width="300" class="text-center">Permisos</th>
                                                                    </thead>
                                                                    <tbody>
                                                                        @foreach ($menu_popups as $menu_popup)
                                                                        <tr>
                                                                            @php
                                                                                $sql = "select id, permissions from menu_popup_ints where menupopup_id = ".$menu_popup->id." and user_id = ".$user_id." and rowstatus = 'ACT'";
                                                                                $idpermissions = DB::select($sql);
                                                                            @endphp
                                                                            @php
                                                                                $permision_id = 0;
                                                                                foreach ($idpermissions as $permission) {
                                                                                    $permision_id = $permission->id;
                                                                                    $permissions = $permission->permissions;
                                                                                }
                                                                            @endphp
                                                                            <td class="text-center">
                                                                                <div class="form-check form-switch">
                                                                                    @if ($permision_id > 0)
                                                                                        <input class="form-check-input" type="checkbox" value="{{$menu_popup->id}}" id="check{{$menu_popup->id}}" name="check{{$menu_popup->id}}" checked>
                                                                                    @else
                                                                                        <input class="form-check-input" type="checkbox" value="{{$menu_popup->id}}" id="check{{$menu_popup->id}}" name="check{{$menu_popup->id}}">
                                                                                    @endif
                                                                                </div>
                                                                            </td>
                                                                            @if ($menu_popup->nivel == 1)
                                                                                <td class="text-left"><i class="{{$menu_popup->icon}}"></i> <b>{{$menu_popup->menupopup_name}}</b></td>
                                                                            @else
                                                                                <td class="text-left"><i class="{{$menu_popup->icon}}"></i> <b>{{$menu_popup->menupopup_name}}</b></td>
                                                                            @endif
                                                                            <td width="300" class="text-left">
                                                                            @if ($menu_popup->href != '')
                                                                                @if ($permision_id > 0)
                                                                                    <select id="selectp{{$menu_popup->id}}" name="selectp{{$menu_popup->id}}" class="select2 form-control">
                                                                                        @if ($permissions == 1)
                                                                                            <option value="1" selected>Solo Consulta</option>
                                                                                        @else
                                                                                            <option value="1">Solo Consulta</option>
                                                                                        @endif
                                                                                        @if ($permissions == 2)
                                                                                            <option value="2" selected>Consultar-Incluir</option>
                                                                                        @else
                                                                                            <option value="2">Consultar-Incluir</option>
                                                                                        @endif
                                                                                        @if ($permissions == 3)
                                                                                            <option value="3" selected>Consultar-Incluir-Editar</option>
                                                                                        @else
                                                                                            <option value="3">Consultar-Incluir-Editar</option>
                                                                                        @endif
                                                                                        @if ($permissions == 4)
                                                                                            <option value="4" selected>Consultar-Incluir-Editar-Eliminar</option>
                                                                                        @else
                                                                                            <option value="4">Consultar-Incluir-Editar-Eliminar</option>
                                                                                        @endif
                                                                                    </select>
                                                                                @else
                                                                                    <select id="selectp{{$menu_popup->id}}" name="selectp{{$menu_popup->id}}" class="select2 form-control">
                                                                                        <option value="1" selected>Solo Consulta</option>
                                                                                        <option value="2">Consultar-Incluir</option>
                                                                                        <option value="3">Consultar-Incluir-Editar</option>
                                                                                        <option value="4">Consultar-Incluir-Editar-Eliminar</option>
                                                                                    </select>
                                                                                @endif
                                                                            @endif
                                                                            </td>
                                                                        </tr>
                                                                        @endforeach
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @else
                                                    <div class="tab-pane fade" id="tab-pane-{{$menu->id}}" role="tabpanel" aria-labelledby="tab-{{$menu->id}}" tabindex="0">
                                                        <div class="row col-md-12 form-group">
                                                            <div class="col-md-12 form-group">
                                                                <br>
                                                                <table id="table{{$menu->id}}" class="table table-striped table-bordered">
                                                                    <thead class="bg-dark text-white">
                                                                        <th width="60" class="text-center">Habilitar</th>
                                                                        <th class="text-center">Nombre del Sub Menú</th>
                                                                        <th width="300" class="text-center">Permisos</th>
                                                                    </thead>
                                                                    <tbody>
                                                                        @foreach ($menu_popups as $menu_popup)
                                                                        <tr>
                                                                            @php
                                                                                $sql = "select id, permissions from menu_popup_ints where menupopup_id = ".$menu_popup->id." and user_id = ".$user_id." and rowstatus = 'ACT'";
                                                                                $idpermissions = DB::select($sql);
                                                                            @endphp
                                                                            @php
                                                                                $permision_id = 0;
                                                                                foreach ($idpermissions as $permission) {
                                                                                    $permision_id = $permission->id;
                                                                                    $permissions = $permission->permissions;
                                                                                }
                                                                            @endphp
                                                                            <td class="text-center">
                                                                                <div class="form-check">
                                                                                    @if ($permision_id > 0)
                                                                                        <input class="form-check-input" type="checkbox" value="{{$menu_popup->id}}" id="check{{$menu_popup->id}}" name="check{{$menu_popup->id}}" checked>
                                                                                    @else
                                                                                        <input class="form-check-input" type="checkbox" value="{{$menu_popup->id}}" id="check{{$menu_popup->id}}" name="check{{$menu_popup->id}}">
                                                                                    @endif
                                                                                </div>
                                                                            </td>
                                                                            @if ($menu_popup->nivel == 1)
                                                                                <td class="text-left"><i class="{{$menu_popup->icon}}"></i> <b>{{$menu_popup->menupopup_name}}</b></td>
                                                                            @else
                                                                                <td class="text-left"><i class="{{$menu_popup->icon}}"></i> <b>{{$menu_popup->menupopup_name}}</b></td>
                                                                            @endif
                                                                            <td width="300" class="text-left">
                                                                            @if ($menu_popup->href != '')
                                                                                @if ($permision_id > 0)
                                                                                    <select id="selectp{{$menu_popup->id}}" name="selectp{{$menu_popup->id}}" class="select2 form-control">
                                                                                        @if ($permissions == 1)
                                                                                            <option value="1" selected>Solo Consulta</option>
                                                                                        @else
                                                                                            <option value="1">Solo Consulta</option>
                                                                                        @endif
                                                                                        @if ($permissions == 2)
                                                                                            <option value="2" selected>Consultar-Incluir</option>
                                                                                        @else
                                                                                            <option value="2">Consultar-Incluir</option>
                                                                                        @endif
                                                                                        @if ($permissions == 3)
                                                                                            <option value="3" selected>Consultar-Incluir-Editar</option>
                                                                                        @else
                                                                                            <option value="3">Consultar-Incluir-Editar</option>
                                                                                        @endif
                                                                                        @if ($permissions == 4)
                                                                                            <option value="4" selected>Consultar-Incluir-Editar-Eliminar</option>
                                                                                        @else
                                                                                            <option value="4">Consultar-Incluir-Editar-Eliminar</option>
                                                                                        @endif
                                                                                    </select>
                                                                                @else
                                                                                    <select id="selectp{{$menu_popup->id}}" name="selectp{{$menu_popup->id}}" class="select2 form-control">
                                                                                        <option value="1" selected>Solo Consulta</option>
                                                                                        <option value="2">Consultar-Incluir</option>
                                                                                        <option value="3">Consultar-Incluir-Editar</option>
                                                                                        <option value="4">Consultar-Incluir-Editar-Eliminar</option>
                                                                                    </select>
                                                                                @endif
                                                                            @endif
                                                                            </td>
                                                                        </tr>
                                                                        @endforeach
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4 form-group">
                                            <label for="selectall" class="form-group">Otorgar este permiso a todos los submenús:</label>
                                            <select id="selectall" name="selectall" class="select2 form-control" onchange="marcar2()">
                                                <option value="1" selected>Solo Consulta</option>
                                                <option value="2">Consultar-Incluir</option>
                                                <option value="3">Consultar-Incluir-Editar</option>
                                                <option value="4">Consultar-Incluir-Editar-Eliminar</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4 form-group">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="" onclick="marcar()" id="marcartodos">
                                                <label class="form-check-label" for="marcartodos">
                                                    <b>Marcar todos los submenús</b>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-2 form-group text-center">
                                            <button type="submit" class="btn btn-success btn-block" tabindex="4">Guardar  <i class="fa fa-save"></i></button>
                                        </div>
                                        <div class="col-md-2 form-group text-center">
                                            <a href="/config" class="btn btn-secondary btn-block" tabindex="5">Cancelar  <i class="fa fa-arrow-circle-left"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script>
        var xmin = document.getElementById("min").value;
        var xmax = document.getElementById("max").value;

        function marcar(){
            if (xmin != 0 && xmax != 0){
                for (let i = xmin; i <= xmax; i++) {
                    if (document.getElementById("marcartodos").checked){
                        if (document.getElementById("check"+i)){
                            document.getElementById("check"+i).checked = true;
                        }
                    } else {
                        if (document.getElementById("check"+i)){
                            document.getElementById("check"+i).checked = false;
                        }
                    }
                }
            }
        }

        function marcar2(){
            var xselectall = document.getElementById("selectall").value;
            if (xmin != 0 && xmax != 0){
                for (let i = xmin; i <= xmax; i++){
                    if (document.getElementById("selectp"+i)){
                        document.getElementById("selectp"+i).value = xselectall;
                    }
                }
            }
        }
    </script>
@stop
