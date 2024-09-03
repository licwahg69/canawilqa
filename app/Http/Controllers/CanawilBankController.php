<?php

namespace App\Http\Controllers;

use App\Models\CanawilBank;
use App\Models\V_canawil_bank;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CanawilBankController extends Controller
{
    private function permissions($case)
    {
        $puser_id = auth()->user()->id;
        $prole = auth()->user()->role;

        switch($case){
            case 1:
                $menu_name = 'MAESTRO DE CATÁLOGOS';
                $menupopup_name = 'Bancos de Canawil';
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
                session(['menupopup_id' => 27]);
                break;
        }

        $permissions = $this->permissions(1);

        // Obtener todas los Bancos con status activo de la BD
        $sql = "SELECT * FROM v_canawil_banks where rowstatus = 'ACT'";
        $banks = DB::select($sql);

        $banks2 = V_canawil_bank::where('rowstatus', 'ACT')->orderBy('countryname', 'asc')->orderBy('bank_name', 'asc')->simplePaginate(10);
        $banks2->withQueryString();

        // retornar la vista Index
        return view('canawilbank.index', compact('banks', 'banks2', 'permissions'));
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
        $canawilbank_id = $request->canawilbank_id;
        $toaction = $request->toaction;

        switch ($toaction){
            case 'new':
                $CanawilBank = new CanawilBank();

                $CanawilBank->country_id = request('country_id');
                $CanawilBank->bank_name = request('bank_name');
                $CanawilBank->account_number = request('account_number');

                $CanawilBank->save();

                $permissions = $this->permissions(1);

                // Obtener todas los Bancos con status activo de la BD
                $sql = "SELECT * FROM v_canawil_banks where rowstatus = 'ACT'";
                $banks = DB::select($sql);

                $banks2 = V_canawil_bank::where('rowstatus', 'ACT')->orderBy('countryname', 'asc')->orderBy('bank_name', 'asc')->simplePaginate(10);
                $banks2->withQueryString();

                // retornar la vista Index
                return view('canawilbank.index', compact('banks', 'banks2', 'permissions'));
                break;
            case 'create':
                $sql = "SELECT * FROM countries where rowstatus = 'ACT'";
                $countries = DB::select($sql);

                return view('canawilbank.create', compact('countries'));
                break;
            case 'edit':
                $banks = CanawilBank::find($canawilbank_id);

                $sql = "SELECT * FROM countries where rowstatus = 'ACT'";
                $countries = DB::select($sql);

                // retornar la vista edit
                return view('canawilbank.edit', compact('banks', 'countries'));
                break;
            case 'update':
                $CanawilBank = CanawilBank::find($canawilbank_id);

                $CanawilBank->country_id = request('country_id');
                $CanawilBank->bank_name = request('bank_name');
                $CanawilBank->account_number = request('account_number');

                $CanawilBank->save();

                $permissions = $this->permissions(1);

                // Obtener todas los Bancos con status activo de la BD
                $sql = "SELECT * FROM v_canawil_banks where rowstatus = 'ACT'";
                $banks = DB::select($sql);

                $banks2 = V_canawil_bank::where('rowstatus', 'ACT')->orderBy('countryname', 'asc')->orderBy('bank_name', 'asc')->simplePaginate(10);
                $banks2->withQueryString();

                // retornar la vista Index
                return view('canawilbank.index', compact('banks', 'banks2', 'permissions'));
                break;
            case 'delete':
                // Borrar el registro logicamente con el id enviado
                $CanawilBank = CanawilBank::find($canawilbank_id);

                $CanawilBank->rowstatus = 'INA';

                $CanawilBank->save();

                $permissions = $this->permissions(1);

                // Obtener todas los Bancos con status activo de la BD
                $sql = "SELECT * FROM v_canawil_banks where rowstatus = 'ACT'";
                $banks = DB::select($sql);

                $banks2 = V_canawil_bank::where('rowstatus', 'ACT')->orderBy('countryname', 'asc')->orderBy('bank_name', 'asc')->simplePaginate(10);
                $banks2->withQueryString();

                // retornar la vista Index
                return view('canawilbank.index', compact('banks', 'banks2', 'permissions'));
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
