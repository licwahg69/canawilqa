<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use App\Models\V_currencies;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CurrencyController extends Controller
{
    private function permissions($case)
    {
        $puser_id = auth()->user()->id;
        $prole = auth()->user()->role;

        switch($case){
            case 1:
                $menu_name = 'MAESTRO DE CATÁLOGOS';
                $menupopup_name = 'Divisas';
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
                session(['menupopup_id' => 5]);
                break;
        }

        $permissions = $this->permissions(1);

        // Obtener todas los Bancos con status activo de la BD
        $sql = "SELECT * FROM v_currencies where rowstatus = 'ACT'";
        $currencies = DB::select($sql);

        $currencies2 = V_currencies::where('rowstatus', 'ACT')->orderBy('countryname', 'asc')->orderBy('description', 'asc')->simplePaginate(10);
        $currencies2->withQueryString();

        // retornar la vista Index
        return view('currency.index', compact('currencies', 'currencies2', 'permissions'));
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
        $currency_id = $request->currency_id;
        $toaction = $request->toaction;

        switch ($toaction){
            case 'new':
                $Currency = new Currency();

                $Currency->country_id = request('country_id');
                $Currency->description = request('description');
                $Currency->currency = request('currency');
                $Currency->symbol = request('symbol');

                $Currency->save();

                $permissions = $this->permissions(1);

                // Obtener todas los Bancos con status activo de la BD
                $sql = "SELECT * FROM v_currencies where rowstatus = 'ACT'";
                $currencies = DB::select($sql);

                $currencies2 = V_currencies::where('rowstatus', 'ACT')->orderBy('countryname', 'asc')->orderBy('description', 'asc')->simplePaginate(10);
                $currencies2->withQueryString();

                // retornar la vista Index
                return view('currency.index', compact('currencies', 'currencies2', 'permissions'));
                break;
            case 'create':
                $sql = "SELECT * FROM countries where rowstatus = 'ACT'";
                $countries = DB::select($sql);

                return view('currency.create', compact('countries'));
                break;
            case 'edit':
                $currencies = Currency::find($currency_id);

                $sql = "SELECT * FROM countries where rowstatus = 'ACT'";
                $countries = DB::select($sql);

                // retornar la vista edit
                return view('currency.edit', compact('currencies', 'countries'));
                break;
            case 'update':
                $Currency = Currency::find($currency_id);

                $Currency->country_id = request('country_id');
                $Currency->description = request('description');
                $Currency->currency = request('currency');
                $Currency->symbol = request('symbol');

                $Currency->save();

                $permissions = $this->permissions(1);

                // Obtener todas los Bancos con status activo de la BD
                $sql = "SELECT * FROM v_currencies where rowstatus = 'ACT'";
                $currencies = DB::select($sql);

                $currencies2 = V_currencies::where('rowstatus', 'ACT')->orderBy('countryname', 'asc')->orderBy('description', 'asc')->simplePaginate(10);
                $currencies2->withQueryString();

                // retornar la vista Index
                return view('currency.index', compact('currencies', 'currencies2', 'permissions'));
                break;
            case 'delete':
                // Borrar el registro logicamente con el id enviado
                $Currency = Currency::find($currency_id);

                $Currency->rowstatus = 'INA';

                $Currency->save();

                $permissions = $this->permissions(1);

                // Obtener todas los Bancos con status activo de la BD
                $sql = "SELECT * FROM v_currencies where rowstatus = 'ACT'";
                $currencies = DB::select($sql);

                $currencies2 = V_currencies::where('rowstatus', 'ACT')->orderBy('countryname', 'asc')->orderBy('description', 'asc')->simplePaginate(10);
                $currencies2->withQueryString();

                // retornar la vista Index
                return view('currency.index', compact('currencies', 'currencies2', 'permissions'));
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
