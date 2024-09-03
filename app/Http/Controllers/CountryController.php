<?php

namespace App\Http\Controllers;

use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CountryController extends Controller
{
    private function permissions($case)
    {
        $puser_id = auth()->user()->id;
        $prole = auth()->user()->role;

        switch ($case) {
            case 1:
                $menu_name = 'MAESTRO DE CATÁLOGOS';
                $menupopup_name = 'Paises';
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
                session(['menupopup_id' => 2]);
                break;
        }

        $permissions = $this->permissions(1);

        $sql = "SELECT * FROM countries where rowstatus = 'ACT'";
        $countries = DB::select($sql);

        $countries2 = Country::where('rowstatus', 'ACT')->orderBy('countryname', 'asc')->simplePaginate(10);
        $countries2->withQueryString();

        return view('country.index', compact('countries', 'countries2', 'permissions'));
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
        $country_id = $request->country_id;
        $toaction = $request->toaction;

        switch ($toaction){
            case 'new':
                $Country = new Country();

                $Country->countryname = request('countryname');
                $Country->phone_code = request('phone_code');

                $Country->save();

                $permissions = $this->permissions(1);

                $sql = "SELECT * FROM countries where rowstatus = 'ACT'";
                $countries = DB::select($sql);

                $countries2 = Country::where('rowstatus', 'ACT')->orderBy('countryname', 'asc')->simplePaginate(10);
                $countries2->withQueryString();

                return view('country.index', compact('countries', 'countries2', 'permissions'));

                break;
            case 'create':
                return view('country.create');

                break;
            case 'edit':
                $countries = Country::find($country_id);

                // retornar la vista edit
                return view('country.edit', compact('countries'));
                break;
            case 'update':
                $Country = Country::find($country_id);

                $Country->countryname = request('countryname');
                $Country->phone_code = request('phone_code');

                $Country->save();

                $permissions = $this->permissions(1);

                $sql = "SELECT * FROM countries where rowstatus = 'ACT'";
                $countries = DB::select($sql);

                $countries2 = Country::where('rowstatus', 'ACT')->orderBy('countryname', 'asc')->simplePaginate(10);
                $countries2->withQueryString();

                return view('country.index', compact('countries', 'countries2', 'permissions'));
                break;
            case 'delete':
                // Borrar el registro logicamente con el id enviado
                $Country = Country::find($country_id);

                $Country->rowstatus = 'INA';

                $Country->save();

                $permissions = $this->permissions(1);

                $sql = "SELECT * FROM countries where rowstatus = 'ACT'";
                $countries = DB::select($sql);

                $countries2 = Country::where('rowstatus', 'ACT')->orderBy('countryname', 'asc')->simplePaginate(10);
                $countries2->withQueryString();

                return view('country.index', compact('countries', 'countries2', 'permissions'));
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
