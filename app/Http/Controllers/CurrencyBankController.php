<?php

namespace App\Http\Controllers;

use App\Models\CurrencyBank;
use App\Models\V_currency_bank;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CurrencyBankController extends Controller
{
    private function permissions($case)
    {
        $puser_id = auth()->user()->id;
        $prole = auth()->user()->role;

        switch($case){
            case 1:
                $menu_name = 'MAESTRO DE CATÁLOGOS';
                $menupopup_name = 'Bancos Destino';
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
                session(['menupopup_id' => 35]);
                break;
        }

        $permissions = $this->permissions(1);

        // Obtener todas los Bancos con status activo de la BD
        $sql = "SELECT * FROM v_currency_banks where rowstatus = 'ACT'";
        $currency_banks = DB::select($sql);

        $currency_banks2 = V_currency_bank::where('rowstatus', 'ACT')->orderBy('countryname', 'asc')->orderBy('bankname', 'asc')->simplePaginate(10);
        $currency_banks2->withQueryString();

        // retornar la vista Index
        return view('currencybank.index', compact('currency_banks', 'currency_banks2', 'permissions'));
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
        $currencybank_id = $request->currencybank_id;
        $toaction = $request->toaction;

        switch ($toaction){
            case 'new':
                $CurrencyBank = new CurrencyBank();

                $CurrencyBank->bank_id = request('bank_id');
                $CurrencyBank->account_number = request('account_number');

                $CurrencyBank->save();

                $permissions = $this->permissions(1);

                // Obtener todas los Bancos con status activo de la BD
                $sql = "SELECT * FROM v_currency_banks where rowstatus = 'ACT'";
                $currency_banks = DB::select($sql);

                $currency_banks2 = V_currency_bank::where('rowstatus', 'ACT')->orderBy('countryname', 'asc')->orderBy('bankname', 'asc')->simplePaginate(10);
                $currency_banks2->withQueryString();

                // retornar la vista Index
                return view('currencybank.index', compact('currency_banks', 'currency_banks2', 'permissions'));
                break;
            case 'create':
                $sql = "SELECT * FROM countries where rowstatus = 'ACT'";
                $countries = DB::select($sql);

                return view('currencybank.create', compact('countries'));
                break;
            case 'edit':
                $currencybank = V_currency_bank::find($currencybank_id);
                $country_id = $currencybank->country_id;

                $sql = "SELECT * FROM countries where rowstatus = 'ACT'";
                $countries = DB::select($sql);

                $sql = "SELECT * FROM banks where country_id = ".$country_id." and rowstatus = 'ACT'";
                $banks = DB::select($sql);

                // retornar la vista edit
                return view('currencybank.edit', compact('currencybank', 'countries', 'banks'));
                break;
            case 'update':
                $CurrencyBank = CurrencyBank::find($currencybank_id);

                $CurrencyBank->bank_id = request('bank_id');
                $CurrencyBank->account_number = request('account_number');

                $CurrencyBank->save();

                $permissions = $this->permissions(1);

                // Obtener todas los Bancos con status activo de la BD
                $sql = "SELECT * FROM v_currency_banks where rowstatus = 'ACT'";
                $currency_banks = DB::select($sql);

                $currency_banks2 = V_currency_bank::where('rowstatus', 'ACT')->orderBy('countryname', 'asc')->orderBy('bankname', 'asc')->simplePaginate(10);
                $currency_banks2->withQueryString();

                // retornar la vista Index
                return view('currencybank.index', compact('currency_banks', 'currency_banks2', 'permissions'));
                break;
            case 'delete':
                // Borrar el registro logicamente con el id enviado
                $CurrencyBank = CurrencyBank::find($currencybank_id);

                $CurrencyBank->rowstatus = 'INA';

                $CurrencyBank->save();

                $permissions = $this->permissions(1);

                // Obtener todas los Bancos con status activo de la BD
                $sql = "SELECT * FROM v_currency_banks where rowstatus = 'ACT'";
                $currency_banks = DB::select($sql);

                $currency_banks2 = V_currency_bank::where('rowstatus', 'ACT')->orderBy('countryname', 'asc')->orderBy('bankname', 'asc')->simplePaginate(10);
                $currency_banks2->withQueryString();

                // retornar la vista Index
                return view('currencybank.index', compact('currency_banks', 'currency_banks2', 'permissions'));
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

    public function get_bank($id)
    {
        $sql = "select * from banks where country_id=".$id." order by bankname";
        $banks = DB::select($sql);

        return $banks;
    }

    public function get_currencybank($id)
    {
        $sql = "select * from v_currency_banks where country_id=".$id." order by bankname";
        $banks = DB::select($sql);

        return $banks;
    }
}
