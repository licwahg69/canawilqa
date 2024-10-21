<?php

namespace App\Http\Controllers;

use App\Models\V_admtransfers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ProfitController extends Controller
{
    private function permissions($case)
    {
        $puser_id = auth()->user()->id;
        $prole = auth()->user()->role;

        switch($case){
            case 1:
                $menu_name = 'REPORTES E INDICADORES';
                $menupopup_name = 'Ganancias';
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
        session(['menupopup_id' => 38]);
        $permissions = $this->permissions(1);

        return view('profit.parameters', compact('permissions'));
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
            case 'month':
                $sql = "SELECT * FROM v_admtransfers WHERE DATE_TRUNC('month', transfer_date) = DATE_TRUNC('month', CURRENT_DATE) AND rowstatus = 'ACT'";
                $transfers = DB::select($sql);

                $currentMonth = Carbon::now()->month;
                $currentYear = Carbon::now()->year;

                $transfers2 = V_admtransfers::whereMonth('transfer_date', $currentMonth)
                                ->whereYear('transfer_date', $currentYear)
                                ->where('rowstatus', 'ACT')
                                ->orderBy('id', 'desc')
                                ->simplePaginate(10);
                $transfers2->withQueryString();

                $datos2 = [];

                $sql = "select distinct a_to_b, currency_id, currency2_id from v_admtransfers where DATE_TRUNC('month', transfer_date) = DATE_TRUNC('month', CURRENT_DATE) and rowstatus = 'ACT'";
                $transfers_sum = DB::select($sql);

                if (!empty($transfers_sum) && count($transfers_sum) > 0){
                    foreach ($transfers_sum as $row3){
                        $a_to_b = $row3->a_to_b;
                        $currency_id = $row3->currency_id;

                        $sql = "select sum(net_amount) as mount_value, sum(canawil_amount_withheld) as canawil_amount_withheld from v_admtransfers
                        where DATE_TRUNC('month', transfer_date) = DATE_TRUNC('month', CURRENT_DATE) and a_to_b = '".$a_to_b."' and rowstatus = 'ACT'";
                        $sum2 = DB::select($sql);
                        $general_mount_value = $sum2[0]->mount_value;
                        $general_canawil_amount_withheld = $sum2[0]->canawil_amount_withheld;

                        $sql = "select * from currencies where id = ".$currency_id."";
                        $currencies1 = DB::select($sql);
                        $symbol = $currencies1[0]->symbol;
                        $currency = $currencies1[0]->currency;

                        $datos2[] = [
                            'a_to_b' => $a_to_b,
                            'general_mount_value' => number_format($general_mount_value,2,',','.').$symbol.' '.$currency,
                            'general_canawil_amount_withheld' => number_format($general_canawil_amount_withheld,2,',','.').$symbol.' '.$currency

                        ];
                    }
                }

                $permissions = $this->permissions(1);

                return view('profit.month', compact('permissions', 'transfers', 'transfers2',
                'datos2'));
                break;
            case 'between':
                $desde = $request->from_auth_date;
                $hasta = $request->to_auth_date;

                $sql = "select * from v_admtransfers where transfer_date between '".$desde."' and '".$hasta."' and rowstatus = 'ACT'";
                $transfers = DB::select($sql);
                $transfers2 = V_admtransfers::whereBetween('transfer_date', [$desde, $hasta])
                    ->where('rowstatus', 'ACT')
                    ->orderBy('id', 'desc')
                    ->simplePaginate(10);
                $transfers2->withQueryString();

                $datos2 = [];

                $sql = "select distinct a_to_b, currency_id, currency2_id from v_admtransfers where transfer_date between '".$desde."' and '".$hasta."' and rowstatus = 'ACT'";
                $transfers_sum = DB::select($sql);

                if (!empty($transfers_sum) && count($transfers_sum) > 0){
                    foreach ($transfers_sum as $row3){
                        $a_to_b = $row3->a_to_b;
                        $currency_id = $row3->currency_id;

                        $sql = "select sum(net_amount) as mount_value, sum(canawil_amount_withheld) as canawil_amount_withheld from v_admtransfers
                        where transfer_date between '".$desde."' and '".$hasta."' and a_to_b = '".$a_to_b."' and rowstatus = 'ACT'";
                        $sum2 = DB::select($sql);
                        $general_mount_value = $sum2[0]->mount_value;
                        $general_canawil_amount_withheld = $sum2[0]->canawil_amount_withheld;

                        $sql = "select * from currencies where id = ".$currency_id."";
                        $currencies1 = DB::select($sql);
                        $symbol = $currencies1[0]->symbol;
                        $currency = $currencies1[0]->currency;

                        $datos2[] = [
                            'a_to_b' => $a_to_b,
                            'general_mount_value' => number_format($general_mount_value,2,',','.').$symbol.' '.$currency,
                            'general_canawil_amount_withheld' => number_format($general_canawil_amount_withheld,2,',','.').$symbol.' '.$currency

                        ];
                    }
                }

                $permissions = $this->permissions(1);

                return view('profit.between', compact('permissions', 'transfers', 'transfers2',
                'datos2', 'desde', 'hasta'));
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
