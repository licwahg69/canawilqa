<?php

namespace App\Http\Controllers;

use App\Models\Conversion;
use App\Models\V_conversion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ConversionController extends Controller
{
    private function permissions($case)
    {
        $puser_id = auth()->user()->id;
        $prole = auth()->user()->role;

        switch($case){
            case 1:
                $menu_name = 'PROCESOS';
                $menupopup_name = 'Actualizar Divisas';
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
                session(['menupopup_id' => 10]);
                break;
        }

        $permissions = $this->permissions(1);

        // Obtener todas los Bancos con status activo de la BD
        $sql = "SELECT * FROM v_conversions where rowstatus = 'ACT'";
        $conversions = DB::select($sql);

        $conversions2 = V_conversion::where('rowstatus', 'ACT')->simplePaginate(10);
        $conversions2->withQueryString();

        // retornar la vista Index
        return view('conversion.index', compact('conversions', 'conversions2', 'permissions'));
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
        $conversion_id = $request->conversion_id;
        $toaction = $request->toaction;

        switch ($toaction){
            case 'create':
                $sql = "SELECT * FROM v_currencies where rowstatus = 'ACT'";
                $currencies = DB::select($sql);

                return view('conversion.create', compact('currencies'));
                break;
            case 'new':
                $Conversion = new Conversion();

                $Conversion->currency_id = request('currency_id');
                $Conversion->currency2_id = request('currency2_id');
                $Conversion->reference_currency_id = request('reference_currency_id');
                $Conversion->typeuser = request('typeuser');
                $Conversion->conversion_value = request('conversion_value');
                $Conversion->reference_conversion_value = request('reference_conversion_value');
                $Conversion->two_decimals = request('twodecimals');

                $Conversion->save();

                $permissions = $this->permissions(1);

                $sql = "SELECT * FROM v_conversions where rowstatus = 'ACT'";
                $conversions = DB::select($sql);

                $conversions2 = V_conversion::where('rowstatus', 'ACT')->simplePaginate(10);
                $conversions2->withQueryString();

                // retornar la vista Index
                return view('conversion.index', compact('conversions', 'conversions2', 'permissions'));
                break;
            case 'edit':
                $sql = "SELECT * FROM v_currencies where rowstatus = 'ACT'";
                $currencies = DB::select($sql);

                $conversion = V_conversion::find($conversion_id);

                // retornar la vista edit
                return view('conversion.edit', compact('conversion', 'currencies'));
                break;
            case 'update':
                $Conversion = Conversion::find($conversion_id);

                $Conversion->conversion_value = request('conversion_value');
                $Conversion->reference_conversion_value = request('reference_conversion_value');
                $Conversion->reference_currency_id = request('reference_currency_id');
                $Conversion->typeuser = request('typeuser');
                $Conversion->two_decimals = request('twodecimals');

                $Conversion->save();

                $permissions = $this->permissions(1);

                $sql = "SELECT * FROM v_conversions where rowstatus = 'ACT'";
                $conversions = DB::select($sql);

                $conversions2 = V_conversion::where('rowstatus', 'ACT')->simplePaginate(10);
                $conversions2->withQueryString();

                // retornar la vista Index
                return view('conversion.index', compact('conversions', 'conversions2', 'permissions'));
                break;
            case 'delete':
                // Borrar el registro logicamente con el id enviado
                $Conversion = Conversion::find($conversion_id);

                $Conversion->rowstatus = 'INA';

                $Conversion->save();

                $permissions = $this->permissions(1);

                $sql = "SELECT * FROM v_conversions where rowstatus = 'ACT'";
                $conversions = DB::select($sql);

                $conversions2 = V_conversion::where('rowstatus', 'ACT')->simplePaginate(10);
                $conversions2->withQueryString();

                // retornar la vista Index
                return view('conversion.index', compact('conversions', 'conversions2', 'permissions'));
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
