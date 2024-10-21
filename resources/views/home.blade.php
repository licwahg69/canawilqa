@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1 class="m-0 text-primary text-center"><b>Cambios CANAWIL</b></h1>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @auth
                        <p class="mb-0">Hola <label class="mb-0 text-success">{{auth()->user()->name}}</label>, te has logueado a <b>CANAWIL</b> correctamente en tu perfil de <label class="mb-0 text-primary">{{session('user_role_name');}}</label></p>
                    @endauth
                    <br>
                    <form action="/force-logout" id="forcelogout" name="forcelogout" method="post">
                        @csrf
                            <div class="col-md-12 form-group">
                                <div class="row">
                                    <img src="/images/home.jpg" style="background-size: cover;" class="d-block w-100" alt="home" id="homepng">
                                </div>
                            </div>
                            <input type="hidden" id="href" name="href" value="">
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop

@section('footer')
<div class="float-right d-sm-inline">
    <label class="text-primary">© {{ date_format(date_create(date("Y")),"Y") }} Cambios CANAWIL</label>, <label>todos los derechos reservados.</label>
</div>
@stop
