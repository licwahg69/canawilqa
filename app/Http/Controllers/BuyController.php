<?php

namespace App\Http\Controllers;

use App\Models\Buy;
use App\Models\V_buy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BuyController extends Controller
{
    private function permissions($case)
    {
        $puser_id = auth()->user()->id;
        $prole = auth()->user()->role;

        switch($case){
            case 1:
                $menu_name = 'PROCESOS';
                $menupopup_name = 'Comprar Divisas';
                break;
            case 2:
                $menu_name = 'REPORTES E INDICADORES';
                $menupopup_name = 'Histórico de Compras';
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
        $pcountry_id = auth()->user()->country_id;
        switch ($prole) {
            case 'ADM':
                session(['menupopup_id' => 34]);
                break;
        }

        $permissions = $this->permissions(1);

        $sql = "SELECT * FROM countries where rowstatus = 'ACT'";
        $countries = DB::select($sql);

        $sql = "SELECT * FROM currencies where country_id = ".$pcountry_id." and rowstatus = 'ACT'";
        $currencies = DB::select($sql);
        $pcurrency = $currencies[0]->currency;
        $psymbol = $currencies[0]->symbol;
        $pcurrency_id = $currencies[0]->id;

        // retornar la vista Index
        return view('buy.buy', compact('permissions', 'countries', 'pcurrency', 'psymbol',
            'pcurrency_id'));
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
            case 'new':
                $Buy = new Buy();

                $Buy->currencybank_id = request('currencybank_id');
                $Buy->currency_id = request('currency_id');
                $Buy->currency2_id = request('currency2_id');
                $Buy->exchange_rate = request('exchange_rate');
                $Buy->purchased_amount = request('real_purchased_amount');
                $Buy->available_amount = request('real_purchased_amount');
                $Buy->converted_amount = request('real_converted_amount');

                $Buy->save();

                return redirect('home');
                break;
            case 'see':
                $buy_id = request('buy_id');

                $sql = "SELECT * FROM v_buys where id = ".$buy_id." and rowstatus = 'ACT'";
                $buys = DB::select($sql);

                return view('buy.see', compact('buys'));
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

    public function historybuy(){
        $puser_id = auth()->user()->id;
        $prole = auth()->user()->role;
        switch ($prole) {
            case 'ADM':
                session(['submenupopup_id' => 31]);
                session(['menupopup_id' => 37]);
                $permissions = $this->permissions(2);
                break;
        }

        // Obtener todas los Bancos con status activo de la BD
        $sql = "SELECT * FROM v_buys where rowstatus = 'ACT'";
        $buys = DB::select($sql);

        $buys2 = V_buy::where('rowstatus', 'ACT')->orderBy('created_at', 'desc')->orderBy('countryname', 'asc')->orderBy('bankname', 'asc')->simplePaginate(10);
        $buys2->withQueryString();

        // retornar la vista Index
        return view('buy.index', compact('buys', 'buys2', 'permissions'));
    }
}
