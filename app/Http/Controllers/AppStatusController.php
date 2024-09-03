<?php

namespace App\Http\Controllers;

use App\Models\AppStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AppStatusController extends Controller
{
    private function permissions($case)
    {
        $puser_id = auth()->user()->id;
        $prole = auth()->user()->role;

        switch($case){
            case 1:
                $menu_name = 'CONFIGURACIÓN';
                $menupopup_name = 'Estatus del Sistema';
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
                session(['menupopup_id' => 19]);
                break;
        }

        $permissions = $this->permissions(1);

        // Obtener todas los Bancos con status activo de la BD
        $sql = "SELECT * FROM app_statuses where rowstatus = 'ACT'";
        $app_statuses = DB::select($sql);

        $app_statuses2 = AppStatus::where('rowstatus', 'ACT')->orderBy('id', 'asc')->simplePaginate(10);
        $app_statuses2->withQueryString();

        // retornar la vista Index
        return view('appstatus.index', compact('app_statuses', 'app_statuses2', 'permissions'));
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
        $toaction = $request->toaction;

        switch ($toaction){
            case 'create':
                return view('appstatus.create');
                break;
            case 'new':
                $AppStatus = new AppStatus();

                $AppStatus->message = request('message');
                $AppStatus->stop = request('stop');

                $AppStatus->save();

                $permissions = $this->permissions(1);

                // Obtener todas los Bancos con status activo de la BD
                $sql = "SELECT * FROM app_statuses where rowstatus = 'ACT'";
                $app_statuses = DB::select($sql);

                $app_statuses2 = AppStatus::where('rowstatus', 'ACT')->orderBy('id', 'asc')->simplePaginate(10);
                $app_statuses2->withQueryString();

                // retornar la vista Index
                return view('appstatus.index', compact('app_statuses', 'app_statuses2', 'permissions'));
                break;
            case 'edit':
                $appstatus_id = $request->appstatus_id;

                $AppStatus = AppStatus::find($appstatus_id);

                $AppStatus->active = 'Y';

                $AppStatus->save();

                $permissions = $this->permissions(1);

                // Obtener todas los Bancos con status activo de la BD
                $sql = "SELECT * FROM app_statuses where rowstatus = 'ACT'";
                $app_statuses = DB::select($sql);

                $app_statuses2 = AppStatus::where('rowstatus', 'ACT')->orderBy('id', 'asc')->simplePaginate(10);
                $app_statuses2->withQueryString();

                // retornar la vista Index
                return view('appstatus.index', compact('app_statuses', 'app_statuses2', 'permissions'));
                break;
            case 'delete':
                $appstatus_id = $request->appstatus_id;

                $AppStatus = AppStatus::find($appstatus_id);

                $AppStatus->rowstatus = 'INA';

                $AppStatus->save();

                $permissions = $this->permissions(1);

                // Obtener todas los Bancos con status activo de la BD
                $sql = "SELECT * FROM app_statuses where rowstatus = 'ACT'";
                $app_statuses = DB::select($sql);

                $app_statuses2 = AppStatus::where('rowstatus', 'ACT')->orderBy('id', 'asc')->simplePaginate(10);
                $app_statuses2->withQueryString();

                // retornar la vista Index
                return view('appstatus.index', compact('app_statuses', 'app_statuses2', 'permissions'));
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
