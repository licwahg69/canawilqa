<?php

namespace App\Http\Controllers;

use App\Models\MenuPopupInt;
use App\Models\User;
use App\Models\V_user;
use App\Models\Location;
use App\Models\Town;
use App\Models\TypeDoc;
use App\Models\Payer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    private function permissions($case)
    {
        $puser_id = auth()->user()->id;
        $prole = auth()->user()->role;

        switch ($case) {
            case 1:
                $menu_name = 'MAESTRO DE CATÁLOGOS';
                $menupopup_name = 'Usuarios';
                break;
            case 2:
                $menu_name = 'CONFIGURACIÓN';
                $menupopup_name = 'Mi Perfil';
                break;
            case 3:
                $menu_name = 'CONFIGURACIÓN';
                $menupopup_name = 'Parametrizar Usuarios';
                break;
        }

        // Obtener id del menu
        $sql = "SELECT id FROM menus where sysrole = '".$prole."' and menu_name = '".$menu_name."' and rowstatus = 'ACT'";
        $menus = DB::select($sql);

        $menu_id = 0;
        foreach ($menus as $menu) {
            $menu_id = $menu->id;
        }

        // Obtener id del popupmenu
        $sql = 'SELECT id FROM menu_popups where menu_id = '.$menu_id." and menupopup_name = '".$menupopup_name."' and rowstatus = 'ACT'";
        $menu_popups = DB::select($sql);

        $menupopup_id = 0;
        foreach ($menu_popups as $menu_popup) {
            $menupopup_id = $menu_popup->id;
        }

        // Obtener permisos del usuario para este modulo
        $sql = 'SELECT permissions FROM menu_popup_ints where menupopup_id = '.$menupopup_id." and user_id = '".$puser_id."' and rowstatus = 'ACT'";
        $menu_popup_ints = DB::select($sql);

        $permissions = 0;
        foreach ($menu_popup_ints as $menu_popup_int) {
            $permissions = $menu_popup_int->permissions;
        }

        return $permissions;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $prole = auth()->user()->role;
        switch ($prole) {
            case 'ADM':
                session(['menupopup_id' => 8]);
                break;
        }

        $permissions = $this->permissions(1);

        $puser_id = 1;

        // Obtener todos los empleados de esa compañia con status activo de la BD
        $sql = "SELECT * FROM v_users where rowstatus = 'ACT' and id not in (".$puser_id.")";
        $users = DB::select($sql);

        $users2 = V_user::where('id', '!=', $puser_id)->where('rowstatus', 'ACT')->orderBy('name', 'asc')->simplePaginate(10);
        $users2->withQueryString();

        return view('user.index', compact('users', 'users2', 'permissions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $prole = auth()->user()->role;
        switch ($prole) {
            case 'ADM':
                session(['menupopup_id' => 1]);
                break;
            case 'ALI':
                session(['menupopup_id' => 12]);
                break;
            case 'USU':
                session(['menupopup_id' => 23]);
                break;
        }

        $user_id = auth()->user()->id;

        $users = V_user::find($user_id);

        $permissions = $this->permissions(2);

        $country_id = $users->country_id;
        $location_id = $users->location_id;

        $sql = "SELECT * FROM locations where country_id = ".$country_id." and rowstatus = 'ACT'";
        $locations = DB::select($sql);

        $sql = "SELECT * FROM towns where location_id = ".$location_id." and rowstatus = 'ACT'";
        $towns = DB::select($sql);

        $sql = "SELECT * FROM type_docs where country_id = ".$country_id." and rowstatus = 'ACT'";
        $type_docs = DB::select($sql);

        $sql = "SELECT * FROM countries where rowstatus = 'ACT'";
        $countries = DB::select($sql);

        return view('user.my_perfil', compact('users','countries', 'locations', 'towns', 'type_docs'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $authuser = auth()->user();
        if ($authuser) {
            $prole = auth()->user()->role;
        }

        $user_id = request('user_id');
        $toaction = request('toaction');

        switch ($toaction){
            case 'foto':
                $namepicture = request('photo_path');

                if ($request->hasFile('files1')) {
                    $routepicture = '/storage/fotos/'.$namepicture;

                    $request->file('files1')->storeAs('/public/fotos/', $namepicture);

                    $User = User::find($user_id);

                    $User->profile_photo_path = $routepicture;

                    $User->save();
                }

                $permissions = $this->permissions(2);

                $users = V_user::find($user_id);

                $country_id = $users->country_id;
                $location_id = $users->location_id;

                $sql = "SELECT * FROM locations where country_id = ".$country_id." and rowstatus = 'ACT'";
                $locations = DB::select($sql);

                $sql = "SELECT * FROM towns where location_id = ".$location_id." and rowstatus = 'ACT'";
                $towns = DB::select($sql);

                $sql = "SELECT * FROM type_docs where country_id = ".$country_id." and rowstatus = 'ACT'";
                $type_docs = DB::select($sql);

                $sql = "SELECT * FROM countries where rowstatus = 'ACT'";
                $countries = DB::select($sql);

                return view('user.my_perfil', compact('users','countries', 'locations', 'towns', 'type_docs'));
                break;
            case 'update':
                $User = User::find($user_id);

                $User->country_id = request('country_id');
                $User->location_id = request('location_id');
                $User->town_id = request('town_id');
                $User->address = request('address');
                $User->name = request('name');
                if (request()->has('comercial_name')) {
                    $User->comercial_name = request('comercial_name');
                }
                $User->email = request('email');
                $User->gender = request('gender');
                $User->cellphone = request('totalphone');
                $User->typedoc_id = request('typedoc_id');
                $User->numdoc = request('numdoc');
                $User->question1 = request('question1');
                $User->answer1 = request('answer1');
                $User->question2 = request('question2');
                $User->answer2 = request('answer2');
                $User->question3 = request('question3');
                $User->answer3 = request('answer3');

                $User->save();

                return redirect('/home');
                break;
            case 'password':
                $password = request('password');

                $User = User::find($user_id);

                $hashedPassword = Hash::make($password);
                $User->password = $hashedPassword;

                $User->save();

                return redirect('/home');
                break;
            case 'userforgot':
                $email = request('email');

                // Obtener los datos del empleado con status activo de la BD
                $sql = "SELECT * FROM v_users where email = '".$email."'  and rowstatus = 'ACT'";
                $users = DB::select($sql);

                if (count($users) > 0) {
                    // La consulta trajo registros
                    $numrandom = random_int(100, 900);

                    return view('user.change', compact('users', 'numrandom'));
                } else {
                    // La consulta no trajo registros
                    return view('user.error404');
                }
                break;
            case 'newpassword':
                $numrandom = request('numrandom');
                $answer = request('answer');
                $user_id = request('user_id');

                $sql = 'SELECT * FROM v_users where id = '.$user_id."  and rowstatus = 'ACT'";
                $users = DB::select($sql);

                foreach ($users as $row) {
                    $answer1 = $row->answer1;
                    $answer2 = $row->answer2;
                    $answer3 = $row->answer3;
                }

                $match = false;
                if ($numrandom >= 100 & $numrandom <= 300) {
                    if ($answer == $answer1) {
                        $match = true;
                    }
                }

                if ($numrandom > 300 & $numrandom <= 600) {
                    if ($answer == $answer2) {
                        $match = true;
                    }
                }

                if ($numrandom > 600 & $numrandom <= 900) {
                    if ($answer == $answer3) {
                        $match = true;
                    }
                }

                if ($match) {
                    return view('user.newpassword', compact('user_id'));
                } else {
                    return view('user.error400');
                }
                break;
            case 'savepassword':
                $user_id = request('user_id');
                $password = request('password');

                $User = User::find($user_id);

                $hashedPassword = Hash::make($password);
                $User->password = $hashedPassword;

                $User->save();

                return redirect('/');
                break;
            case 'config':
                $sql = 'SELECT * FROM v_users where id = '.$user_id." and rowstatus = 'ACT'";
                $users = DB::select($sql);

                $role = $users[0]->role;

                $sql = "SELECT * FROM menus where sysrole = '".$role."' and rowstatus = 'ACT'".' order by menuorder';
                $menus = DB::select($sql);

                $sql = "SELECT min(id) as min, max(id) as max FROM menu_popups where menu_id in (select id from menus where sysrole = '".$role."' and rowstatus = 'ACT') and rowstatus = 'ACT'";
                $maxmin = DB::select($sql);

                $min = 0;
                $max = 0;
                foreach ($maxmin as $row) {
                    $min = $row->min;
                    $max = $row->max;
                }

                return view('user.access', compact('users', 'menus', 'min', 'max'));
                break;
            case 'access':
                $role = request('role');

                // primero borramos todos los accesos de ese usuario en la tabla menu_popup_ints
                $sql = 'DELETE FROM menu_popup_ints where user_id = '.$user_id.'';
                DB::select($sql);

                // ahora creamos un ciclo para guardar los accesos recibidos
                $min = request('min');
                $max = request('max');

                for ($i = $min; $i <= $max; ++$i) {
                    if (request('check'.$i) !== null) {
                        if ($request->has('check'.$i)) {
                            $menupopup_id = request('check'.$i);
                            if (request('selectp'.$i) !== null) {
                                $permissions = request('selectp'.$i);
                            } else {
                                $permissions = 1;
                            }
                            $MenuPopupInt = new MenuPopupInt();

                            $MenuPopupInt->menupopup_id = $menupopup_id;
                            $MenuPopupInt->user_id = $user_id;
                            $MenuPopupInt->permissions = $permissions;

                            $MenuPopupInt->save();
                        }
                    }
                }

                $permissions = $this->permissions(3);

                // Obtener todaos los empleados de esa compañia con status activo de la BD
                $sql = "SELECT * FROM v_users where role = 'ADM' and rowstatus = 'ACT'";
                $users = DB::select($sql);

                $users2 = V_user::where('role', 'ADM')->where('rowstatus', 'ACT')->orderBy('name', 'asc')->simplePaginate(10);
                $users2->withQueryString();

                return view('user.config', compact('users', 'users2', 'permissions'));
                break;
            case 'view':
                $users = V_user::find($user_id);

                return view('user.my_perfil_view', compact('users'));
                break;
            case 'update2':
                // Borrar el registro logicamente con el id enviado
                $User = User::find($user_id);

                $User->credit = request('credit');
                $User->credit_limit = request('credit_limit');

                $User->save();

                $permissions = $this->permissions(1);

                $puser_id = auth()->user()->id;

                // Obtener todos los empleados de esa compañia con status activo de la BD
                $sql = "SELECT * FROM v_users where rowstatus = 'ACT' and id not in (".$puser_id.")";
                $users = DB::select($sql);

                $users2 = V_user::where('id', '!=', $puser_id)->where('rowstatus', 'ACT')->orderBy('name', 'asc')->simplePaginate(10);
                $users2->withQueryString();

                return view('user.index', compact('users', 'users2', 'permissions'));
                break;
            case 'delete':
                // Borrar el registro logicamente con el id enviado
                $User = User::find($user_id);

                $User->email = 'delete'.$user_id.'@canawil.com';
                $User->rowstatus = 'INA';

                $User->save();

                $permissions = $this->permissions(1);

                $puser_id = auth()->user()->id;

                // Obtener todos los empleados de esa compañia con status activo de la BD
                $sql = "SELECT * FROM v_users where rowstatus = 'ACT' and id not in (".$puser_id.")";
                $users = DB::select($sql);

                $users2 = V_user::where('id', '!=', $puser_id)->where('rowstatus', 'ACT')->orderBy('name', 'asc')->simplePaginate(10);
                $users2->withQueryString();

                return view('user.index', compact('users', 'users2', 'permissions'));
                break;
            case 'passworduser':
                $User = User::find($user_id);

                $role = $User->role;
                $payer_name = $User->name;

                $User->country_id = request('country_id');
                $User->location_id = request('location_id');
                $User->town_id = request('town_id');
                $User->address = request('address');
                if (request()->has('comercial_name')) {
                    $User->comercial_name = request('comercial_name');
                }
                $User->cellphone = request('totalphone');
                $User->typedoc_id = request('typedoc_id');
                $User->numdoc = request('numdoc');
                $User->question1 = request('question1');
                $User->answer1 = request('answer1');
                $User->question2 = request('question2');
                $User->answer2 = request('answer2');
                $User->question3 = request('question3');
                $User->answer3 = request('answer3');
                $password = request('password');
                $hashedPassword = Hash::make($password);
                $User->password = $hashedPassword;

                $User->save();

                // se crean los accesos al menu de este usuario
                // Se obtienen todos las opciones de menú
                $sql = "SELECT * FROM menu_popups where menu_id in (select id from menus where sysrole = '".$role."')";
                $menu_popups = DB::select($sql);

                // se crean los accesos al menu y los permisos del nuevo usuario
                foreach ($menu_popups as $menu_popup) {
                    $menu_popup_id = $menu_popup->id;

                    $MenuPopupInt = new MenuPopupInt();

                    $MenuPopupInt->menupopup_id = $menu_popup_id;
                    $MenuPopupInt->user_id = $user_id;
                    $MenuPopupInt->permissions = 4;

                    $MenuPopupInt->save();
                }

                if ($role == 'USU'){
                    $Payer = new Payer();

                    $Payer->user_id = $user_id;
                    $Payer->payer_name = $payer_name;
                    $Payer->cellphone = request('totalphone');

                    $Payer->save();
                }

                return redirect('/');
                break;
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
    }

    protected function login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ], [
            'email.required' => 'El Email es requerido',
            'email.email' => 'El Email no tiene el formato correcto',
            'password.required' => 'La contraseña es requerida',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            // Authentication successful
            $user_rowstatus = auth()->user()->rowstatus;

            if ($user_rowstatus == 'ACT') {
                Auth::logoutOtherDevices($request->password);

                $puser_id = auth()->user()->id;

                $sql = "SELECT role_name FROM v_users where id = ".$puser_id."";
                $users = DB::select($sql);
                $role_name = $users[0]->role_name;

                session(['user_role_name' => $role_name]);

                return redirect('/home');
            } else {
                Auth::logout();

                $request->session()->invalidate();

                $request->session()->regenerateToken();

                return view('user.error405');
            }
        }

        // Authentication failed
        return back()->withErrors(['email' => 'Las credenciales no coinciden con nuestra BD']);
    }

    public function forgotp()
    {
        return view('user.forgot');
    }

    public function changep()
    {
        $prole = auth()->user()->role;
        switch ($prole) {
            case 'ADM':
                session(['menupopup_id' => 6]);
                break;
            case 'ALI':
                session(['menupopup_id' => 13]);
                break;
            case 'USU':
                session(['menupopup_id' => 24]);
                break;
        }

        $user_id = auth()->user()->id;

        $users = User::find($user_id);

        $numrandom = random_int(100, 900);

        return view('user.password', compact('users', 'numrandom'));
    }

    public function config()
    {
        $prole = auth()->user()->role;
        switch ($prole) {
            case 'ADM':
                session(['menupopup_id' => 7]);
                break;
        }

        $permissions = $this->permissions(3);

        // Obtener todaos los empleados de esa compañia con status activo de la BD
        $sql = "SELECT * FROM v_users where role = 'ADM' and rowstatus = 'ACT'";
        $users = DB::select($sql);

        $users2 = V_user::where('role', 'ADM')->where('rowstatus', 'ACT')->orderBy('name', 'asc')->simplePaginate(10);
        $users2->withQueryString();

        return view('user.config', compact('users', 'users2', 'permissions'));
    }

    public function loginrecord()
    {
        $prole = auth()->user()->role;
        switch ($prole) {
            case 'LOC':
                session(['menupopup_id' => 29]);
                break;
            case 'GER':
                session(['menupopup_id' => 30]);
                break;
            case 'SUP':
                session(['menupopup_id' => 27]);
                break;
        }

        $permissions = $this->permissions(3);

        $company_id = auth()->user()->company_id;

        // Obtener todaos los empleados de esa compañia con status activo de la BD
        $sql = 'SELECT * FROM v_users where company_id = '.$company_id." and rowstatus = 'ACT'";
        $users = DB::select($sql);

        // Obtener nombre de esa compañia
        $sql = 'SELECT company_name FROM companies where id = '.$company_id.'';
        $companies = DB::select($sql);

        $company_name = '';
        foreach ($companies as $row) {
            $company_name = $row->company_name;
        }

        return view('user.loginrecord', compact('users', 'company_name', 'company_id', 'permissions'));
    }

    public function user_password($xemail, $xtoken)
    {
        // Obtener los datos de la tabla users para verificar
        // el correo con el token y que no este vencido el mismo
        $sql = "SELECT * FROM users where email = '".$xemail."' and email_token = '".$xtoken."'";
        $users = DB::select($sql);

        $id = 0;
        foreach ($users as $user) {
            $id = $user->id;
            $user_name = $user->name;
        }

        if ($id > 0) {
            $sql = "SELECT * FROM countries where rowstatus = 'ACT'";
            $countries = DB::select($sql);

            return view('user.user_password', compact('id', 'user_name', 'xemail', 'users', 'countries'));
        } else {
            return view('user.e404');
        }
    }

    public function email($email)
    {
        $sql = "select id from users where email='".$email."'";
        $emails = DB::select($sql);

        $id = 0;
        foreach ($emails as $row2) {
            $id = $row2->id;
        }

        if ($id > 0){
            $response = 'S';
        } else {
            $response = 'N';
        }

        $datos = [];

        $datos['response'] = $response;

        return $datos;
    }

    public function code($id)
    {
        $sql = "select phone_code from countries where id='".$id."'";
        $country = DB::select($sql);

        $phonecode = '';
        foreach ($country as $row2) {
            $phonecode = $row2->phone_code;
        }

        $datos = [];

        $datos['phonecode'] = $phonecode;

        return $datos;
    }

    public function location($id)
    {
        return Location::where('country_id', $id)->where('rowstatus', 'ACT')->orderBy('name_location', 'asc')->get();
    }

    public function town($id)
    {
        return Town::where('location_id', $id)->where('rowstatus', 'ACT')->orderBy('name_town', 'asc')->get();
    }

    public function doc($id)
    {
        return TypeDoc::where('country_id', $id)->where('rowstatus', 'ACT')->orderBy('description', 'asc')->get();
    }
}
