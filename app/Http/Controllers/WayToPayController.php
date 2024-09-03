<?php

namespace App\Http\Controllers;

use App\Models\WayToPay;
use App\Models\V_way_to_pay;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WayToPayController extends Controller
{
    private function permissions($case)
    {
        $puser_id = auth()->user()->id;
        $prole = auth()->user()->role;

        switch($case){
            case 1:
                $menu_name = 'MAESTRO DE CATÁLOGOS';
                $menupopup_name = 'Instrumentos Bancarios';
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
                session(['submenupopup_id' => 4]);
                session(['menupopup_id' => 11]);
                break;
        }

        $permissions = $this->permissions(1);

        // Obtener todas los Bancos con status activo de la BD
        $sql = "SELECT * FROM v_way_to_pays where rowstatus = 'ACT'";
        $waytopays = DB::select($sql);

        $waytopays2 = V_way_to_pay::where('rowstatus', 'ACT')->orderBy('countryname', 'asc')->orderBy('description', 'asc')->simplePaginate(10);
        $waytopays2->withQueryString();

        // retornar la vista Index
        return view('waytopay.index', compact('waytopays', 'waytopays2', 'permissions'));
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
        $waytopay_id = $request->waytopay_id;
        $toaction = $request->toaction;

        switch ($toaction){
            case 'new':
                $WayToPay = new WayToPay();

                $WayToPay->country_id = request('country_id');
                $WayToPay->description = request('description');
                $WayToPay->reference = request('reference');

                $WayToPay->save();

                $permissions = $this->permissions(1);

                // Obtener todas los Bancos con status activo de la BD
                $sql = "SELECT * FROM v_way_to_pays where rowstatus = 'ACT'";
                $waytopays = DB::select($sql);

                $waytopays2 = V_way_to_pay::where('rowstatus', 'ACT')->orderBy('countryname', 'asc')->orderBy('description', 'asc')->simplePaginate(10);
                $waytopays2->withQueryString();

                // retornar la vista Index
                return view('waytopay.index', compact('waytopays', 'waytopays2', 'permissions'));
                break;
            case 'create':
                $sql = "SELECT * FROM countries where rowstatus = 'ACT'";
                $countries = DB::select($sql);

                return view('waytopay.create', compact('countries'));
                break;
            case 'edit':
                $waytopays = WayToPay::find($waytopay_id);

                $sql = "SELECT * FROM countries where rowstatus = 'ACT'";
                $countries = DB::select($sql);

                // retornar la vista edit
                return view('waytopay.edit', compact('waytopays', 'countries'));
                break;
            case 'update':
                $WayToPay = WayToPay::find($waytopay_id);

                $WayToPay->country_id = request('country_id');
                $WayToPay->description = request('description');
                $WayToPay->reference = request('reference');

                $WayToPay->save();

                $permissions = $this->permissions(1);

                // Obtener todas los Bancos con status activo de la BD
                $sql = "SELECT * FROM v_way_to_pays where rowstatus = 'ACT'";
                $waytopays = DB::select($sql);

                $waytopays2 = V_way_to_pay::where('rowstatus', 'ACT')->orderBy('countryname', 'asc')->orderBy('description', 'asc')->simplePaginate(10);
                $waytopays2->withQueryString();

                // retornar la vista Index
                return view('waytopay.index', compact('waytopays', 'waytopays2', 'permissions'));
                break;
            case 'delete':
                // Borrar el registro logicamente con el id enviado
                $WayToPay = WayToPay::find($waytopay_id);

                $WayToPay->rowstatus = 'INA';

                $WayToPay->save();

                $permissions = $this->permissions(1);

                // Obtener todas los Bancos con status activo de la BD
                $sql = "SELECT * FROM v_way_to_pays where rowstatus = 'ACT'";
                $waytopays = DB::select($sql);

                $waytopays2 = V_way_to_pay::where('rowstatus', 'ACT')->orderBy('countryname', 'asc')->orderBy('description', 'asc')->simplePaginate(10);
                $waytopays2->withQueryString();

                // retornar la vista Index
                return view('waytopay.index', compact('waytopays', 'waytopays2', 'permissions'));
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
