<footer class="main-footer" style="padding: 5px; font-size: 14px">
    @if (session('setting_app') == 'APP')
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 form-group" id="footer_message" style='display: block;'>
                    <h4><b>Aviso de Canawil: <label style="color:red">{{session('message_app')}}</label></b></h4>
                </div>
                <div class="col-md-12 form-group" id="footer_message_mob" style='display: none;'>
                    <b>Aviso de Canawil: <label style="color:red">{{session('message_app')}}</label></b>
                </div>
            </div>
        </div>
    @endif
    @yield('footer')
</footer>

