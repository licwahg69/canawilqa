<?php

namespace App\Http\Controllers;

use App\Models\V_transaction;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ResendImageController extends Controller
{
    private function permissions($case)
    {
        $puser_id = auth()->user()->id;
        $prole = auth()->user()->role;

        switch($case){
            case 1:
                $menu_name = 'PROCESOS';
                $menupopup_name = 'Reenviar Imagen';
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
        $puser_id = auth()->user()->id;
        $prole = auth()->user()->role;
        switch ($prole) {
            case 'ALI':
                session(['menupopup_id' => 17]);
                break;
            case 'USU':
                session(['menupopup_id' => 22]);
                break;
        }

        $permissions = $this->permissions(1);

        $sql = "SELECT * FROM v_transactions where user_id = ".$puser_id." and sendstatus = 'PEN' and rowstatus = 'ACT'";
        $transactions = DB::select($sql);

        $transactions2 = V_transaction::where('user_id', $puser_id)->where('sendstatus', 'PEN')->where('rowstatus', 'ACT')->orderBy('id', 'desc')->simplePaginate(10);
        $transactions2->withQueryString();

        return view('transaction.resendimage', compact('permissions', 'transactions', 'transactions2'));
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
            case 'resend':
                $transaction_id = request('transaction_id');
                $type_screen = request('type_screen');

                $credit = auth()->user()->credit;

                $creditcash = 'Y';
                if ($credit == 'Y'){
                    $sql = "SELECT id FROM credits where transaction_id = ".$transaction_id." and rowstatus = 'ACT'";
                    $credits = DB::select($sql);
                    $credit_id = 0;
                    foreach ($credits as $row2) {
                        $credit_id = $row2->id;
                    }
                    if ($credit_id > 0){
                        $creditcash = 'N';
                    }
                }

                if($type_screen == 'W'){
                    return view('transaction.photoweb', compact('transaction_id', 'credit', 'creditcash'));
                } else {
                    return view('transaction.photo', compact('transaction_id', 'credit', 'creditcash'));
                }
                break;
            case 'delete':
                $transaction_id = $request->transaction_id;

                $Transaction = Transaction::find($transaction_id);

                $Transaction->rowstatus = 'INA';

                $Transaction->save();

                $puser_id = auth()->user()->id;
                $prole = auth()->user()->role;
                switch ($prole) {
                    case 'ALI':
                        session(['menupopup_id' => 17]);
                        break;
                    case 'USU':
                        session(['menupopup_id' => 22]);
                        break;
                }

                $permissions = $this->permissions(1);

                $sql = "SELECT * FROM v_transactions where user_id = ".$puser_id." and sendstatus = 'PEN' and rowstatus = 'ACT'";
                $transactions = DB::select($sql);

                $transactions2 = V_transaction::where('user_id', $puser_id)->where('sendstatus', 'PEN')->where('rowstatus', 'ACT')->orderBy('id', 'desc')->simplePaginate(10);
                $transactions2->withQueryString();

                return view('transaction.resendimage', compact('permissions', 'transactions', 'transactions2'));
                break;
            case 'see':
                $transaction_id = $request->transaction_id;

                $sql = "SELECT * FROM v_transfers where transaction_id = ".$transaction_id." and rowstatus = 'ACT'";
                $transfers = DB::select($sql);

                $sql = "SELECT * FROM v_transactions where id = ".$transaction_id." and rowstatus = 'ACT'";
                $transactions = DB::select($sql);
                $cellphone = $transactions[0]->cellphone;

                $sql = "SELECT phone_code FROM countries WHERE '".$cellphone."' LIKE phone_code || '%'";
                $phonecode = DB::select($sql);
                $phone_code = $phonecode[0]->phone_code;

                $onlycellphone = str_replace($phone_code, '', $cellphone);

                return view('transaction.see', compact('transactions', 'phone_code', 'onlycellphone',
                'transfers'));
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
