<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\V_bank;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BankController extends Controller
{
    private function permissions($case)
    {
        $puser_id = auth()->user()->id;
        $prole = auth()->user()->role;

        switch($case){
            case 1:
                $menu_name = 'MAESTRO DE CATÁLOGOS';
                $menupopup_name = 'Bancos';
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
                session(['menupopup_id' => 3]);
                break;
        }

        $permissions = $this->permissions(1);

        // Obtener todas los Bancos con status activo de la BD
        $sql = "SELECT * FROM v_banks where rowstatus = 'ACT'";
        $banks = DB::select($sql);

        $banks2 = V_bank::where('rowstatus', 'ACT')->orderBy('countryname', 'asc')->orderBy('bankname', 'asc')->simplePaginate(10);
        $banks2->withQueryString();

        // retornar la vista Index
        return view('bank.index', compact('banks', 'banks2', 'permissions'));
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
        $bank_id = $request->bank_id;
        $toaction = $request->toaction;

        switch ($toaction){
            case 'new':
                $Bank = new Bank();

                $Bank->country_id = request('country_id');
                $Bank->bankname = request('bankname');
                if(!is_null(request('prefix'))){
                    $Bank->prefix = request('prefix');
                }

                $Bank->save();

                $permissions = $this->permissions(1);

                // Obtener todas los Bancos con status activo de la BD
                $sql = "SELECT * FROM v_banks where rowstatus = 'ACT'";
                $banks = DB::select($sql);

                $banks2 = V_bank::where('rowstatus', 'ACT')->orderBy('countryname', 'asc')->orderBy('bankname', 'asc')->simplePaginate(10);
                $banks2->withQueryString();

                // retornar la vista Index
                return view('bank.index', compact('banks', 'banks2', 'permissions'));
                break;
            case 'create':
                $sql = "SELECT * FROM countries where rowstatus = 'ACT'";
                $countries = DB::select($sql);

                return view('bank.create', compact('countries'));
                break;
            case 'edit':
                $banks = Bank::find($bank_id);

                $sql = "SELECT * FROM countries where rowstatus = 'ACT'";
                $countries = DB::select($sql);

                // retornar la vista edit
                return view('bank.edit', compact('banks', 'countries'));
                break;
            case 'update':
                $Bank = Bank::find($bank_id);

                $Bank->country_id = request('country_id');
                $Bank->bankname = request('bankname');
                if(!is_null(request('prefix'))){
                    $Bank->prefix = request('prefix');
                } else {
                    $Bank->prefix = '';
                }

                $Bank->save();

                $permissions = $this->permissions(1);

                // Obtener todas los Bancos con status activo de la BD
                $sql = "SELECT * FROM v_banks where rowstatus = 'ACT'";
                $banks = DB::select($sql);

                $banks2 = V_bank::where('rowstatus', 'ACT')->orderBy('countryname', 'asc')->orderBy('bankname', 'asc')->simplePaginate(10);
                $banks2->withQueryString();

                // retornar la vista Index
                return view('bank.index', compact('banks', 'banks2', 'permissions'));
                break;
            case 'delete':
                // Borrar el registro logicamente con el id enviado
                $Bank = Bank::find($bank_id);

                $Bank->rowstatus = 'INA';

                $Bank->save();

                $permissions = $this->permissions(1);

                // Obtener todas los Bancos con status activo de la BD
                $sql = "SELECT * FROM v_banks where rowstatus = 'ACT'";
                $banks = DB::select($sql);

                $banks2 = V_bank::where('rowstatus', 'ACT')->orderBy('countryname', 'asc')->orderBy('bankname', 'asc')->simplePaginate(10);
                $banks2->withQueryString();

                // retornar la vista Index
                return view('bank.index', compact('banks', 'banks2', 'permissions'));
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
