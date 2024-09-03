<?php

namespace App\Http\Controllers;

use App\Mail\MailHPfree;
use App\Models\User;
use App\Models\Hpemail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class LicenseController extends Controller
{
    private function permissions($case)
    {
        $puser_id = auth()->user()->id;
        $prole = auth()->user()->role;

        switch ($case) {
            case 1:
                $menu_name = 'PROCESOS';
                $menupopup_name = 'Enviar Licencias';
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
                session(['menupopup_id' => 9]);
                break;
        }

        return view('license.new');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $toaction = request('toaction');

        switch ($toaction){
            case 'create':
                $email = request('email');
                $fecha_actual = Carbon::now();
                $email_token = '26ce7fcd703c918cb411e9397a2e8580f72a85a6c532b7d26ea397a2b36b';

                $User = new User();

                $User->password = '';
                $User->name = request('name');
                $User->gender = request('gender');
                $User->role = request('role');
                if (request('gender') == 'MAS'){
                    $User->profile_photo_path = '/avatar_sinfotom.png';
                } else {
                    $User->profile_photo_path = '/avatar_sinfotof.png';
                }
                $User->email = $email;
                $User->email_token = $email_token;
                $User->email_verified_at = $fecha_actual;

                $User->save();

                $url = env('EMAIL_USER_ADDRESS').$email.'/'.$email_token.' ';

                $Hpemail = new Hpemail();

                $Hpemail->name = request('name');
                $Hpemail->gender = request('gender');
                $Hpemail->role = request('role');
                $Hpemail->email = $email;
                $Hpemail->url = $url;

                $Hpemail->save();

                Mail::to($email)->send(new MailHPfree($Hpemail));

                return redirect('/home');
                break;
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
