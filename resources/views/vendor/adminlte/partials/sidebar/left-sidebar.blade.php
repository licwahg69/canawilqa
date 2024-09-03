<aside class="main-sidebar {{ config('adminlte.classes_sidebar', 'sidebar-dark-primary elevation-4') }}">

    {{-- Sidebar brand logo --}}
    @if(config('adminlte.logo_img_xl'))
        @include('adminlte::partials.common.brand-logo-xl')
    @else
        @include('adminlte::partials.common.brand-logo-xs')
    @endif

    {{-- Sidebar menu --}}
    <div class="sidebar">
        <nav class="pt-2">
            <ul class="nav nav-pills nav-sidebar flex-column {{ config('adminlte.classes_sidebar_nav', '') }}"
                data-widget="treeview" role="menu"
                @if(config('adminlte.sidebar_nav_animation_speed') != 300)
                    data-animation-speed="{{ config('adminlte.sidebar_nav_animation_speed') }}"
                @endif
                @if(!config('adminlte.sidebar_nav_accordion'))
                    data-accordion="false"
                @endif>
                {{-- Configured sidebar links
                @each('adminlte::partials.sidebar.menu-item', $adminlte->menu('sidebar'), 'item') --}}


                @php
                    $authuser = auth()->user();

                    use Illuminate\Support\Facades\DB;

                    $sysrole = $authuser->role;
                    $user_id = $authuser->id;

                    $endline = 'N';

                    $sql = "SELECT * FROM app_statuses where active = 'Y' and rowstatus = 'ACT'";
                    $app_statuses = DB::select($sql);
                    $setting_app = $app_statuses[0]->setting;
                    $message_app = $app_statuses[0]->message;
                    $stop = $app_statuses[0]->stop;

                    session(['setting_app' => $setting_app]);
                    session(['message_app' => $message_app]);

                    $sql = "select distinct menu_id as id, menu_name, menu_order, sysrole from v_menu_popups_ints where user_id = ".$user_id." order by menu_order";
                    $menus = DB::select($sql);
                    $usersysrole = $menus[0]->sysrole;
                @endphp

                @if ($authuser)
                    @if ($stop == 'NO' || $usersysrole == 'ADM')
                        @foreach ($menus as $menu)
                            @if ($endline == 'N')
                                <li  class="nav-header text-primary">
                                    {{$menu->menu_name}}
                                </li>
                            @else
                                    </ul>
                                </li>
                                <li  class="nav-header text-primary">
                                    {{$menu->menu_name}}
                                </li>
                                @php
                                    $endline = 'N';
                                @endphp
                            @endif
                            @php
                                $menu_id = $menu->id;
                                $sql = "select * from v_menu_popups_ints where user_id = ".$user_id." and menu_id = ".$menu_id." order by menu_order,menupopup_order";
                                $users = DB::select($sql);
                            @endphp
                            @foreach ($users as $user)
                                @if ($user->nivel == 1)
                                    @if ($user->endnivel2 == 'Y')
                                        @if ($endline == 'N')
                                            <li  class="nav-item">
                                                @if (session('menupopup_id') == $user->menupopup_id)
                                                    <a class="nav-link active" href="#" onclick="document.getElementById('href').value = '{{$user->href}}'; document.getElementById('forcelogout').submit();">
                                                @else
                                                    <a class="nav-link" href="#" onclick="document.getElementById('href').value = '{{$user->href}}'; document.getElementById('forcelogout').submit();">
                                                @endif
                                                    <i class="{{$user->icon}}"></i>
                                                    <p> {{$user->menupopup_name}}</p>
                                                </a>
                                            </li>
                                        @else
                                                </ul>
                                            </li>
                                            <li  class="nav-item">
                                                @if (session('menupopup_id') == $user->menupopup_id)
                                                    <a class="nav-link active" href="#" onclick="document.getElementById('href').value = '{{$user->href}}'; document.getElementById('forcelogout').submit();">
                                                @else
                                                    <a class="nav-link" href="#" onclick="document.getElementById('href').value = '{{$user->href}}'; document.getElementById('forcelogout').submit();">
                                                @endif
                                                    <i class="{{$user->icon}}"></i>
                                                    <p> {{$user->menupopup_name}}</p>
                                                </a>
                                            </li>
                                            @php
                                                $endline = 'Y';
                                            @endphp
                                        @endif
                                    @else
                                        @if ($endline == 'N')
                                            @if (session('submenupopup_id') == $user->menupopup_id)
                                                <li  class="nav-item has-treeview menu-open">
                                                    <a class="nav-link active" href="">
                                            @else
                                                <li  class="nav-item has-treeview">
                                                    <a class="nav-link" href="">
                                            @endif
                                                    <i class="{{$user->icon}}"></i>
                                                    @if ($user->menupopup_name == 'Transacciones' && $user->sysrole == 'ADM')
                                                        @php
                                                            $sql = "select count(*) as nrow from transactions where sendstatus not in ('PEN', 'TRA') and rowstatus = 'ACT'";
                                                            $nrows = DB::select($sql);
                                                            $nrow = $nrows[0]->nrow;
                                                        @endphp
                                                        @if ($nrow > 0)
                                                            <p> {{$user->menupopup_name}} <i class="fas fa-angle-left right"></i> <span class="badge badge-danger right">{{$nrow}}</span></p>
                                                        @else
                                                            <p> {{$user->menupopup_name}} <i class="fas fa-angle-left right"></i></p>
                                                        @endif
                                                    @else
                                                        <p> {{$user->menupopup_name}} <i class="fas fa-angle-left right"></i></p>
                                                    @endif
                                                </a>
                                                <ul class="nav nav-treeview">
                                            @php
                                                $endline = 'Y';
                                            @endphp
                                        @else
                                                </ul>
                                            </li>
                                            @if (session('submenupopup_id') == $user->menupopup_id)
                                                <li  class="nav-item has-treeview menu-open">
                                                    <a class="nav-link active" href="">
                                            @else
                                                <li  class="nav-item has-treeview">
                                                    <a class="nav-link" href="">
                                            @endif
                                                    <i class="{{$user->icon}}"></i>
                                                    <p> {{$user->menupopup_name}} <i class="fas fa-angle-left right"></i></p>
                                                </a>
                                                <ul class="nav nav-treeview">
                                            @php
                                                $endline = 'Y';
                                            @endphp
                                        @endif
                                    @endif
                                @endif
                                @if ($user->nivel == 2)
                                    <li  class="nav-item">
                                            @if (session('menupopup_id') == $user->menupopup_id)
                                                <a class="nav-link active" href="#" onclick="document.getElementById('href').value = '{{$user->href}}'; document.getElementById('forcelogout').submit();">
                                            @else
                                                <a class="nav-link" href="#" onclick="document.getElementById('href').value = '{{$user->href}}'; document.getElementById('forcelogout').submit();">
                                            @endif
                                            <i class="{{$user->icon}}"></i>
                                            @if ($user->menupopup_name == 'Enviadas/Recibidas' && $user->sysrole == 'ADM')
                                                @php
                                                    $sql = "select count(*) as nrow from transactions where sendstatus in ('ENV', 'REC') and rowstatus = 'ACT'";
                                                    $nrows2 = DB::select($sql);
                                                    $nrow2 = $nrows2[0]->nrow;
                                                @endphp
                                                @if ($nrow2 > 0)
                                                    <p> {{$user->menupopup_name}} <span class="badge badge-primary right">{{$nrow2}}</span></p>
                                                @else
                                                    <p> {{$user->menupopup_name}}</p>
                                                @endif
                                            @else
                                                @if ($user->menupopup_name == 'En proceso' && $user->sysrole == 'ADM')
                                                    @php
                                                        $sql = "select count(*) as nrow from transactions where sendstatus in ('PRO') and rowstatus = 'ACT'";
                                                        $nrows3 = DB::select($sql);
                                                        $nrow3 = $nrows3[0]->nrow;
                                                    @endphp
                                                    @if ($nrow3 > 0)
                                                        <p> {{$user->menupopup_name}} <span class="badge badge-warning right">{{$nrow3}}</span></p>
                                                    @else
                                                        <p> {{$user->menupopup_name}}</p>
                                                    @endif
                                                @else
                                                    <p> {{$user->menupopup_name}}</p>
                                                @endif
                                            @endif
                                        </a>
                                    </li>
                                @endif
                            @endforeach
                        @endforeach
                    @endif
                @endif

            </ul>
        </nav>
    </div>

</aside>
