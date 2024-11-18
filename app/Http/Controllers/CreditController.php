<?php

namespace App\Http\Controllers;

use App\Models\Pay;
use App\Models\PayDetail;
use App\Models\Credit;
use App\Models\V_credit;
use App\Models\V_pay;
use App\Models\V_pay_detail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CreditController extends Controller
{
    private function permissions($case)
    {
        $puser_id = auth()->user()->id;
        $prole = auth()->user()->role;

        switch($case){
            case 1:
                $menu_name = 'PROCESOS';
                $menupopup_name = 'Pagar Crédito';
                break;
            case 2:
                $menu_name = 'REPORTES E INDICADORES';
                $menupopup_name = 'Créditos pendientes';
                break;
            case 3:
                $menu_name = 'REPORTES';
                $menupopup_name = 'Pagos';
                break;
            case 4:
                $menu_name = 'REPORTES E INDICADORES';
                $menupopup_name = 'Créditos pagados';
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
        $pcountry_id = auth()->user()->country_id;

        switch ($prole) {
            case 'ADM':
                session(['submenupopup_id' => 29]);
                session(['menupopup_id' => 20]);
                break;
            case 'ALI':
                session(['menupopup_id' => 32]);
                break;
            case 'USU':
                session(['menupopup_id' => 33]);
                break;
        }

        $permissions = $this->permissions(1);

        $sql = "SELECT * FROM v_credits where user_id = ".$puser_id." and creditstatus = 'PEN' and rowstatus = 'ACT'";
        $credits = DB::select($sql);

        $credits2 = V_credit::where('user_id', $puser_id)
        ->where('creditstatus', 'PEN')
        ->where('rowstatus', 'ACT')
        ->simplePaginate(10);
        $credits2->withQueryString();

        $sql = "SELECT * FROM canawil_banks where country_id = ".$pcountry_id." and type = 'CON' and rowstatus = 'ACT'";
        $canawil_banks = DB::select($sql);

        return view('credit.index', compact('permissions', 'credits', 'credits2', 'canawil_banks'));
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
            case 'see_pendcredit':
                $puser_id = $request->user_id;

                $sql = "SELECT * FROM v_credits where user_id = ".$puser_id." and creditstatus = 'PEN' and rowstatus = 'ACT'";
                $credits = DB::select($sql);

                $credits2 = V_credit::where('user_id', $puser_id)
                ->where('creditstatus', 'PEN')
                ->where('rowstatus', 'ACT')
                ->simplePaginate(10);
                $credits2->withQueryString();

                $sql = "select sum(net_amount) as net_amount from v_credits where user_id = ".$puser_id."
                and creditstatus = 'PEN' and rowstatus = 'ACT'";
                $sumcredits = DB::select($sql);
                $net_amount = $sumcredits[0]->net_amount;

                $permissions = $this->permissions(2);

                return view('credit.see_pendcredit', compact('permissions', 'credits',
                'credits2', 'net_amount'));
                break;
            case 'det_pendcredit':
                $transaction_id = $request->transaction_id;

                $sql = "SELECT * FROM v_transactions where id = ".$transaction_id." and rowstatus = 'ACT'";
                $transactions = DB::select($sql);
                $cellphone = $transactions[0]->cellphone;

                $sql = "SELECT * FROM v_transfers where transaction_id = ".$transaction_id." and rowstatus = 'ACT'";
                $transfers = DB::select($sql);

                $sql = "SELECT phone_code FROM countries WHERE '".$cellphone."' LIKE phone_code || '%'";
                $phonecode = DB::select($sql);
                $phone_code = $phonecode[0]->phone_code;

                $onlycellphone = str_replace($phone_code, '', $cellphone);

                $sql = "SELECT * FROM v_transferbuys where transaction_id = ".$transaction_id." and rowstatus = 'ACT'";
                $transferbuys = DB::select($sql);

                return view('credit.see_adm', compact('transactions', 'phone_code',
                    'onlycellphone', 'transfers', 'transferbuys'));
                break;
            case 'pay';
                $puser_id = auth()->user()->id;
                $canawilbank_id = $request->canawilbank_id;
                $orientation = $request->orientation;
                $pay_amount = $request->total_amount;

                if ($request->has('imageData')){
                    // Obtener el dato de la imagen
                    $imageData  = $request->input('imageData');

                    // Decodificar la imagen base64 (remover la parte inicial de la cadena de base64)
                    $imageParts = explode(";base64,", $imageData);
                    $imageTypeAux = explode("image/", $imageParts[0]);
                    $imageType = $imageTypeAux[1]; // Obtener la extensión (png, jpg, etc.)
                    $imageBase64 = base64_decode($imageParts[1]);

                    $sql = "SELECT max(id) as id FROM pays";
                    $paysid = DB::select($sql);
                    $bpay_id = 0;
                    foreach ($paysid as $row){
                        $bpay_id = $row->id;
                    }

                    $bpay_id++;

                    $filename = '/storage/images/pays/Image'.$bpay_id.'.'.$imageType;

                    // Guardar la imagen en el almacenamiento
                    Storage::put('public/images/pays/Image'.$bpay_id.'.'.$imageType, $imageBase64);

                    // Captura el valor del campo 'selected_ids' desde el request
                    $selectedIdsString = $request->input('selected_ids');

                    // Convierte la cadena separada por comas en un array
                    if ($selectedIdsString) {
                        $selectedIds = explode(',', $selectedIdsString);
                    } else {
                        $selectedIds = [];
                    }

                    $veces = 0;
                    $preconcept = '';
                    foreach ($selectedIds as $selectedId){
                        $credit_id = $selectedId;
                        $sql = "SELECT transaction_id FROM credits where id = ".$credit_id." and rowstatus = 'ACT'";
                        $credits = DB::select($sql);
                        $transaction_id = $credits[0]->transaction_id;

                        $veces++;
                        $preconcept = $preconcept.'# '.$transaction_id.', ';
                    }

                    $preconcept = rtrim(trim($preconcept), ',');

                    if ($veces > 1){
                        $concepto = 'Pago de las transacciones a crédito '.$preconcept.'.';
                    } else {
                        $concepto = 'Pago de la transacción a crédito '.$preconcept.'.';
                    }

                    $Pay = new Pay();

                    $Pay->user_id = $puser_id;
                    $Pay->canawilbank_id = $canawilbank_id;
                    $Pay->concept = $concepto;
                    $Pay->pay_amount = $pay_amount;
                    $Pay->bank_image = $filename;
                    $Pay->orientation = $orientation;

                    $Pay->save();

                    $pay_id = $Pay->id;

                    foreach ($selectedIds as $selectedId){
                        $credit_id = $selectedId;

                        $PayDetail = new PayDetail();

                        $PayDetail->pay_id = $pay_id;
                        $PayDetail->credit_id = $credit_id;

                        $PayDetail->save();

                        $Credit = Credit::find($credit_id);

                        $Credit->creditstatus = 'PAG';

                        $Credit->save();
                    }
                }

                return redirect('home');
                break;
            case 'seepay';
                $pay_id = $request->pay_id;

                $sql = "SELECT * FROM v_pays where id = ".$pay_id." and rowstatus = 'ACT'";
                $pays = DB::select($sql);

                $sql = "SELECT * FROM v_pay_details where pay_id = ".$pay_id." and rowstatus = 'ACT'";
                $pay_details = DB::select($sql);

                $pay_details2 = V_pay_detail::where('pay_id', $pay_id)->where('rowstatus', 'ACT')->simplePaginate(10);
                $pay_details2->withQueryString();

                return view('credit.see_viewpay', compact('pays', 'pay_details', 'pay_details2'));
                break;
            case 'see_paycredit':
                $puser_id = $request->user_id;

                $permissions = $this->permissions(4);

                $sql = "SELECT * FROM v_pays where user_id = ".$puser_id." and rowstatus = 'ACT'";
                $pays = DB::select($sql);

                $pays2 = V_pay::where('rowstatus', 'ACT')->where('user_id', $puser_id)
                ->orderBy('id', 'desc')->simplePaginate(10);
                $pays2->withQueryString();

                // retornar la vista Index
                return view('credit.admviewpay', compact('pays', 'pays2', 'permissions'));
                break;
            case 'admseepay':
                $pay_id = $request->pay_id;

                $sql = "SELECT * FROM v_pays where id = ".$pay_id." and rowstatus = 'ACT'";
                $pays = DB::select($sql);

                $sql = "SELECT * FROM v_pay_details where pay_id = ".$pay_id." and rowstatus = 'ACT'";
                $pay_details = DB::select($sql);

                $pay_details2 = V_pay_detail::where('pay_id', $pay_id)->where('rowstatus', 'ACT')->simplePaginate(10);
                $pay_details2->withQueryString();

                return view('credit.see_admviewpay', compact('pays', 'pay_details', 'pay_details2'));
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

    public function pendcredit()
    {
        session(['submenupopup_id' => 39]);
        session(['menupopup_id' => 40]);

        $permissions = $this->permissions(2);

        $datos = [];

        $sql = "select distinct user_id, user_name, currency, symbol from v_credits where
        creditstatus = 'PEN' and rowstatus = 'ACT'";
        $credits = DB::select($sql);

        if (!empty($credits) && count($credits) > 0){
            foreach ($credits as $row){
                $user_id = $row->user_id;
                $user_name = $row->user_name;
                $currency = $row->currency;
                $symbol = $row->symbol;

                $sql = "select sum(net_amount) as net_amount from v_credits where user_id = ".$user_id."
                and creditstatus = 'PEN' and rowstatus = 'ACT'";
                $sumcredits = DB::select($sql);
                $net_amount = $sumcredits[0]->net_amount;

                $datos[] = [
                    'user_id' => $user_id,
                    'user_name' => $user_name,
                    'net_amount' => number_format($net_amount,2,',','.').$symbol.' '.$currency,
                ];
            }
        }

        return view('credit.pendcredit', compact('permissions', 'datos'));
    }

    public function paycredit()
    {
        session(['submenupopup_id' => 39]);
        session(['menupopup_id' => 41]);

        $permissions = $this->permissions(4);

        $datos = [];

        $sql = "select distinct user_id, comercial_name from v_pays where
        rowstatus = 'ACT'";
        $pays = DB::select($sql);

        if (!empty($pays) && count($pays) > 0){
            foreach ($pays as $row){
                $user_id = $row->user_id;
                $comercial_name = $row->comercial_name;

                $datos[] = [
                    'user_id' => $user_id,
                    'user_name' => $comercial_name,
                ];
            }
        }

        return view('credit.paycredit', compact('permissions', 'datos'));
    }

    public function viewpay()
    {
        $puser_id = auth()->user()->id;
        $prole = auth()->user()->role;

        switch ($prole) {
            case 'ALI':
                session(['menupopup_id' => 42]);
                break;
            case 'USU':
                session(['menupopup_id' => 43]);
                break;
        }

        $permissions = $this->permissions(3);

        $sql = "SELECT * FROM v_pays where user_id = ".$puser_id." and rowstatus = 'ACT'";
        $pays = DB::select($sql);

        $pays2 = V_pay::where('rowstatus', 'ACT')->where('user_id', $puser_id)
        ->orderBy('id', 'desc')->simplePaginate(10);
        $pays2->withQueryString();

        // retornar la vista Index
        return view('credit.viewpay', compact('pays', 'pays2', 'permissions'));
    }
}
