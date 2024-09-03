<?php

namespace App\Http\Controllers;

use App\Models\V_transaction;
use App\Models\V_transfer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HistoryController extends Controller
{
    private function permissions($case)
    {
        $puser_id = auth()->user()->id;
        $prole = auth()->user()->role;

        switch($case){
            case 1:
                $menu_name = 'REPORTES';
                $menupopup_name = 'Histórico';
                break;
            case 2:
                $menu_name = 'REPORTES E INDICADORES';
                $menupopup_name = 'Histórico';
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
                session(['menupopup_id' => 31]);
                $permissions = $this->permissions(2);
                break;
            case 'ALI':
                session(['menupopup_id' => 18]);
                $permissions = $this->permissions(1);
                break;
            case 'USU':
                session(['menupopup_id' => 26]);
                $permissions = $this->permissions(1);
                break;
        }

        switch ($prole) {
            case 'ADM':
                $sql = "SELECT * FROM v_users where role <> 'ADM' and rowstatus = 'ACT' order by show_comercial_name";
                $users = DB::select($sql);

                $sql = "SELECT * FROM v_documents where rowstatus = 'ACT' order by complete_description";
                $documents = DB::select($sql);

                return view('history.admparameters', compact('permissions', 'users', 'documents'));
                break;
            default:
                return view('history.parameters', compact('permissions'));
                break;
        }
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
            case 'report':
                $puser_id = auth()->user()->id;
                $prole = auth()->user()->role;

                $desde = $request->from_auth_date;
                $hasta = $request->to_auth_date;

                switch($prole){
                    case 'ADM':
                        $user_id = $request->user_id;
                        $document_id = $request->document_id;

                        $permissions = $this->permissions(2);

                        // No hay parametros adicionales, se quiere todo
                        if($user_id == 'ALL' && $document_id == 'ALL'){
                            $sql = "SELECT * FROM v_transfers where transfer_date between '".$desde."' and '".$hasta."' and rowstatus = 'ACT'";
                            $transfers = DB::select($sql);

                            $transfers2 = V_transfer::whereBetween('transfer_date', [$desde, $hasta])
                                ->where('rowstatus', 'ACT')
                                ->orderBy('id', 'desc')
                                ->simplePaginate(10);
                            $transfers2->withQueryString();

                            $datos = [];
                            $datos2 = [];

                            $sql = "select distinct conversion_id from v_transfers where transfer_date between '".$desde."' and '".$hasta."' and rowstatus = 'ACT'";
                            $transfers_conversion = DB::select($sql);

                            if (!empty($transfers_conversion) && count($transfers_conversion) > 0){
                                foreach ($transfers_conversion as $row2){
                                    $conversion_id = $row2->conversion_id;

                                    $sql = "select * from v_conversions where id = ".$conversion_id." and rowstatus = 'ACT'";
                                    $conversions = DB::select($sql);
                                    $typeuser_char = $conversions[0]->typeuser_char;
                                    $currency_description = $conversions[0]->currency_description;
                                    $symbol = $conversions[0]->symbol;
                                    $currency = $conversions[0]->currency;
                                    $currency_description2 = $conversions[0]->currency_description2;
                                    $symbol2 = $conversions[0]->symbol2;
                                    $currency2 = $conversions[0]->currency2;

                                    $sql = "select sum(mount_value) as mount_value, sum(mount_change) as mount_change from v_transfers
                                    where transfer_date between '".$desde."' and '".$hasta."' and conversion_id = ".$conversion_id." and rowstatus = 'ACT'";
                                    $sum = DB::select($sql);
                                    $total_mount_value = $sum[0]->mount_value;
                                    $total_mount_change = $sum[0]->mount_change;

                                    $datos[] = [
                                        'typeuser_char' => $typeuser_char,
                                        'divisa1' => $currency_description,
                                        'mount_value' => $total_mount_value,
                                        'total_mount_value' => number_format($total_mount_value,2).$symbol.' '.$currency,
                                        'divisa2' => $currency_description2,
                                        'mount_change' => $total_mount_change,
                                        'total_mount_change' => number_format($total_mount_change,2).$symbol2.' '.$currency2
                                    ];
                                }
                            }

                            $sql = "select distinct a_to_b, currency_id, currency2_id from v_transfers where transfer_date between '".$desde."' and '".$hasta."' and rowstatus = 'ACT'";
                            $transfers_sum = DB::select($sql);

                            if (!empty($transfers_sum) && count($transfers_sum) > 0){
                                foreach ($transfers_sum as $row3){
                                    $a_to_b = $row3->a_to_b;
                                    $currency_id = $row3->currency_id;
                                    $currency2_id = $row3->currency2_id;

                                    $sql = "select sum(mount_value) as mount_value, sum(mount_change) as mount_change from v_transfers
                                    where transfer_date between '".$desde."' and '".$hasta."' and a_to_b = '".$a_to_b."' and rowstatus = 'ACT'";
                                    $sum2 = DB::select($sql);
                                    $general_mount_value = $sum2[0]->mount_value;
                                    $general_mount_change = $sum2[0]->mount_change;

                                    $sql = "select * from currencies where id = ".$currency_id."";
                                    $currencies1 = DB::select($sql);
                                    $symbol = $currencies1[0]->symbol;
                                    $currency = $currencies1[0]->currency;

                                    $sql = "select * from currencies where id = ".$currency2_id."";
                                    $currencies2 = DB::select($sql);
                                    $symbol2 = $currencies2[0]->symbol;
                                    $currency2 = $currencies2[0]->currency;

                                    $datos2[] = [
                                        'a_to_b' => $a_to_b,
                                        'general_mount_value' => number_format($general_mount_value,2).$symbol.' '.$currency,
                                        'general_mount_change' => number_format($general_mount_change,2).$symbol2.' '.$currency2
                                    ];
                                }
                            }

                            return view('history.history_admin1', compact('permissions', 'transfers', 'transfers2',
                            'datos', 'datos2', 'desde', 'hasta'));
                        }

                        // Se especifica solo el usuario y todos los documentos
                        if($user_id != 'ALL' && $document_id == 'ALL'){
                            $sql = "SELECT * FROM v_transfers where transfer_date between '".$desde."' and '".$hasta."' and user_id = ".$user_id." and rowstatus = 'ACT'";
                            $transfers = DB::select($sql);

                            $transfers2 = V_transfer::whereBetween('transfer_date', [$desde, $hasta])
                                ->where('user_id', $user_id)
                                ->where('rowstatus', 'ACT')
                                ->orderBy('id', 'desc')
                                ->simplePaginate(10);
                            $transfers2->withQueryString();

                            $datos = [];
                            $datos2 = [];

                            $sql = "select distinct conversion_id from v_transfers where transfer_date between '".$desde."' and '".$hasta."' and user_id = ".$user_id." and rowstatus = 'ACT'";
                            $transfers_conversion = DB::select($sql);

                            if (!empty($transfers_conversion) && count($transfers_conversion) > 0){
                                foreach ($transfers_conversion as $row2){
                                    $conversion_id = $row2->conversion_id;

                                    $sql = "select * from v_conversions where id = ".$conversion_id." and rowstatus = 'ACT'";
                                    $conversions = DB::select($sql);
                                    $typeuser_char = $conversions[0]->typeuser_char;
                                    $currency_description = $conversions[0]->currency_description;
                                    $symbol = $conversions[0]->symbol;
                                    $currency = $conversions[0]->currency;
                                    $currency_description2 = $conversions[0]->currency_description2;
                                    $symbol2 = $conversions[0]->symbol2;
                                    $currency2 = $conversions[0]->currency2;

                                    $sql = "select sum(mount_value) as mount_value, sum(mount_change) as mount_change from v_transfers
                                    where transfer_date between '".$desde."' and '".$hasta."' and user_id = ".$user_id." and conversion_id = ".$conversion_id." and rowstatus = 'ACT'";
                                    $sum = DB::select($sql);
                                    $total_mount_value = $sum[0]->mount_value;
                                    $total_mount_change = $sum[0]->mount_change;

                                    $datos[] = [
                                        'typeuser_char' => $typeuser_char,
                                        'divisa1' => $currency_description,
                                        'mount_value' => $total_mount_value,
                                        'total_mount_value' => number_format($total_mount_value,2).$symbol.' '.$currency,
                                        'divisa2' => $currency_description2,
                                        'mount_change' => $total_mount_change,
                                        'total_mount_change' => number_format($total_mount_change,2).$symbol2.' '.$currency2
                                    ];
                                }
                            }

                            $sql = "select distinct a_to_b, currency_id, currency2_id from v_transfers where transfer_date between '".$desde."' and '".$hasta."' and user_id = ".$user_id." and rowstatus = 'ACT'";
                            $transfers_sum = DB::select($sql);

                            if (!empty($transfers_sum) && count($transfers_sum) > 0){
                                foreach ($transfers_sum as $row3){
                                    $a_to_b = $row3->a_to_b;
                                    $currency_id = $row3->currency_id;
                                    $currency2_id = $row3->currency2_id;

                                    $sql = "select sum(mount_value) as mount_value, sum(mount_change) as mount_change from v_transfers
                                    where transfer_date between '".$desde."' and '".$hasta."' and user_id = ".$user_id." and a_to_b = '".$a_to_b."' and rowstatus = 'ACT'";
                                    $sum2 = DB::select($sql);
                                    $general_mount_value = $sum2[0]->mount_value;
                                    $general_mount_change = $sum2[0]->mount_change;

                                    $sql = "select * from currencies where id = ".$currency_id."";
                                    $currencies1 = DB::select($sql);
                                    $symbol = $currencies1[0]->symbol;
                                    $currency = $currencies1[0]->currency;

                                    $sql = "select * from currencies where id = ".$currency2_id."";
                                    $currencies2 = DB::select($sql);
                                    $symbol2 = $currencies2[0]->symbol;
                                    $currency2 = $currencies2[0]->currency;

                                    $datos2[] = [
                                        'a_to_b' => $a_to_b,
                                        'general_mount_value' => number_format($general_mount_value,2).$symbol.' '.$currency,
                                        'general_mount_change' => number_format($general_mount_change,2).$symbol2.' '.$currency2
                                    ];
                                }
                            }

                            return view('history.history_admin2', compact('permissions', 'transfers', 'transfers2',
                            'datos', 'datos2', 'desde', 'hasta'));
                        }

                        // Se especifica solo el documento y todos los usuarios
                        if($user_id == 'ALL' && $document_id != 'ALL'){
                            $sql = "SELECT * FROM v_transfers where transfer_date between '".$desde."' and '".$hasta."' and document_id = ".$document_id." and rowstatus = 'ACT'";
                            $transfers = DB::select($sql);

                            if (!empty($transfers) && count($transfers) > 0){
                                $complete_description = $transfers[0]->complete_description;
                            } else {
                                $complete_description = '';
                            }

                            $transfers2 = V_transfer::whereBetween('transfer_date', [$desde, $hasta])
                                ->where('document_id', $document_id)
                                ->where('rowstatus', 'ACT')
                                ->orderBy('id', 'desc')
                                ->simplePaginate(10);
                            $transfers2->withQueryString();

                            $datos = [];
                            $datos2 = [];

                            $sql = "select distinct conversion_id from v_transfers where transfer_date between '".$desde."' and '".$hasta."' and document_id = ".$document_id." and rowstatus = 'ACT'";
                            $transfers_conversion = DB::select($sql);

                            if (!empty($transfers_conversion) && count($transfers_conversion) > 0){
                                foreach ($transfers_conversion as $row2){
                                    $conversion_id = $row2->conversion_id;

                                    $sql = "select * from v_conversions where id = ".$conversion_id." and rowstatus = 'ACT'";
                                    $conversions = DB::select($sql);
                                    $typeuser_char = $conversions[0]->typeuser_char;
                                    $currency_description = $conversions[0]->currency_description;
                                    $symbol = $conversions[0]->symbol;
                                    $currency = $conversions[0]->currency;
                                    $currency_description2 = $conversions[0]->currency_description2;
                                    $symbol2 = $conversions[0]->symbol2;
                                    $currency2 = $conversions[0]->currency2;

                                    $sql = "select sum(mount_value) as mount_value, sum(mount_change) as mount_change from v_transfers
                                    where transfer_date between '".$desde."' and '".$hasta."' and document_id = ".$document_id." and conversion_id = ".$conversion_id." and rowstatus = 'ACT'";
                                    $sum = DB::select($sql);
                                    $total_mount_value = $sum[0]->mount_value;
                                    $total_mount_change = $sum[0]->mount_change;

                                    $datos[] = [
                                        'typeuser_char' => $typeuser_char,
                                        'divisa1' => $currency_description,
                                        'mount_value' => $total_mount_value,
                                        'total_mount_value' => number_format($total_mount_value,2).$symbol.' '.$currency,
                                        'divisa2' => $currency_description2,
                                        'mount_change' => $total_mount_change,
                                        'total_mount_change' => number_format($total_mount_change,2).$symbol2.' '.$currency2
                                    ];
                                }
                            }

                            $sql = "select distinct a_to_b, currency_id, currency2_id from v_transfers where transfer_date between '".$desde."' and '".$hasta."' and document_id = ".$document_id." and rowstatus = 'ACT'";
                            $transfers_sum = DB::select($sql);

                            if (!empty($transfers_sum) && count($transfers_sum) > 0){
                                foreach ($transfers_sum as $row3){
                                    $a_to_b = $row3->a_to_b;
                                    $currency_id = $row3->currency_id;
                                    $currency2_id = $row3->currency2_id;

                                    $sql = "select sum(mount_value) as mount_value, sum(mount_change) as mount_change from v_transfers
                                    where transfer_date between '".$desde."' and '".$hasta."' and document_id = ".$document_id." and a_to_b = '".$a_to_b."' and rowstatus = 'ACT'";
                                    $sum2 = DB::select($sql);
                                    $general_mount_value = $sum2[0]->mount_value;
                                    $general_mount_change = $sum2[0]->mount_change;

                                    $sql = "select * from currencies where id = ".$currency_id."";
                                    $currencies1 = DB::select($sql);
                                    $symbol = $currencies1[0]->symbol;
                                    $currency = $currencies1[0]->currency;

                                    $sql = "select * from currencies where id = ".$currency2_id."";
                                    $currencies2 = DB::select($sql);
                                    $symbol2 = $currencies2[0]->symbol;
                                    $currency2 = $currencies2[0]->currency;

                                    $datos2[] = [
                                        'a_to_b' => $a_to_b,
                                        'general_mount_value' => number_format($general_mount_value,2).$symbol.' '.$currency,
                                        'general_mount_change' => number_format($general_mount_change,2).$symbol2.' '.$currency2
                                    ];
                                }
                            }

                            return view('history.history_admin3', compact('permissions', 'transfers', 'transfers2',
                            'datos', 'datos2', 'desde', 'hasta', 'complete_description'));
                        }

                        // Solo los que coincidan con usuario y documento
                        if($user_id != 'ALL' && $document_id != 'ALL'){
                            $sql = "SELECT * FROM v_transfers where transfer_date between '".$desde."' and '".$hasta."' and user_id = ".$user_id." and document_id = ".$document_id." and rowstatus = 'ACT'";
                            $transfers = DB::select($sql);

                            if (!empty($transfers) && count($transfers) > 0){
                                $complete_description = $transfers[0]->complete_description;
                            } else {
                                $complete_description = '';
                            }

                            $transfers2 = V_transfer::whereBetween('transfer_date', [$desde, $hasta])
                                ->where('user_id', $user_id)
                                ->where('document_id', $document_id)
                                ->where('rowstatus', 'ACT')
                                ->orderBy('id', 'desc')
                                ->simplePaginate(10);
                            $transfers2->withQueryString();

                            $datos = [];
                            $datos2 = [];

                            $sql = "select distinct conversion_id from v_transfers where transfer_date between '".$desde."' and '".$hasta."' and user_id = ".$user_id." and document_id = ".$document_id." and rowstatus = 'ACT'";
                            $transfers_conversion = DB::select($sql);

                            if (!empty($transfers_conversion) && count($transfers_conversion) > 0){
                                foreach ($transfers_conversion as $row2){
                                    $conversion_id = $row2->conversion_id;

                                    $sql = "select * from v_conversions where id = ".$conversion_id." and rowstatus = 'ACT'";
                                    $conversions = DB::select($sql);
                                    $typeuser_char = $conversions[0]->typeuser_char;
                                    $currency_description = $conversions[0]->currency_description;
                                    $symbol = $conversions[0]->symbol;
                                    $currency = $conversions[0]->currency;
                                    $currency_description2 = $conversions[0]->currency_description2;
                                    $symbol2 = $conversions[0]->symbol2;
                                    $currency2 = $conversions[0]->currency2;

                                    $sql = "select sum(mount_value) as mount_value, sum(mount_change) as mount_change from v_transfers
                                    where transfer_date between '".$desde."' and '".$hasta."' and user_id = ".$user_id." and document_id = ".$document_id." and conversion_id = ".$conversion_id." and rowstatus = 'ACT'";
                                    $sum = DB::select($sql);
                                    $total_mount_value = $sum[0]->mount_value;
                                    $total_mount_change = $sum[0]->mount_change;

                                    $datos[] = [
                                        'typeuser_char' => $typeuser_char,
                                        'divisa1' => $currency_description,
                                        'mount_value' => $total_mount_value,
                                        'total_mount_value' => number_format($total_mount_value,2).$symbol.' '.$currency,
                                        'divisa2' => $currency_description2,
                                        'mount_change' => $total_mount_change,
                                        'total_mount_change' => number_format($total_mount_change,2).$symbol2.' '.$currency2
                                    ];
                                }
                            }

                            $sql = "select distinct a_to_b, currency_id, currency2_id from v_transfers where transfer_date between '".$desde."' and '".$hasta."' and user_id = ".$user_id." and document_id = ".$document_id." and rowstatus = 'ACT'";
                            $transfers_sum = DB::select($sql);

                            if (!empty($transfers_sum) && count($transfers_sum) > 0){
                                foreach ($transfers_sum as $row3){
                                    $a_to_b = $row3->a_to_b;
                                    $currency_id = $row3->currency_id;
                                    $currency2_id = $row3->currency2_id;

                                    $sql = "select sum(mount_value) as mount_value, sum(mount_change) as mount_change from v_transfers
                                    where transfer_date between '".$desde."' and '".$hasta."' and user_id = ".$user_id." and document_id = ".$document_id." and a_to_b = '".$a_to_b."' and rowstatus = 'ACT'";
                                    $sum2 = DB::select($sql);
                                    $general_mount_value = $sum2[0]->mount_value;
                                    $general_mount_change = $sum2[0]->mount_change;

                                    $sql = "select * from currencies where id = ".$currency_id."";
                                    $currencies1 = DB::select($sql);
                                    $symbol = $currencies1[0]->symbol;
                                    $currency = $currencies1[0]->currency;

                                    $sql = "select * from currencies where id = ".$currency2_id."";
                                    $currencies2 = DB::select($sql);
                                    $symbol2 = $currencies2[0]->symbol;
                                    $currency2 = $currencies2[0]->currency;

                                    $datos2[] = [
                                        'a_to_b' => $a_to_b,
                                        'general_mount_value' => number_format($general_mount_value,2).$symbol.' '.$currency,
                                        'general_mount_change' => number_format($general_mount_change,2).$symbol2.' '.$currency2
                                    ];
                                }
                            }

                            return view('history.history_admin4', compact('permissions', 'transfers', 'transfers2',
                            'datos', 'datos2', 'desde', 'hasta', 'complete_description'));
                        }

                        break;
                    default:
                        $sql = "SELECT * FROM v_transactions where user_id = ".$puser_id." and send_date between '".$desde."' and '".$hasta."' and sendstatus <> 'PEN' and rowstatus = 'ACT'";
                        $transactions = DB::select($sql);

                        $datos2 = [];

                        $sql = "select distinct conversion_id, a_to_b, currency_id from v_transactions where user_id = ".$puser_id." and send_date between '".$desde."' and '".$hasta."' and sendstatus <> 'PEN' and rowstatus = 'ACT'";
                        $transactions_sum = DB::select($sql);

                        if (!empty($transactions_sum) && count($transactions_sum) > 0){
                            foreach ($transactions_sum as $row2){
                                $conversion_id = $row2->conversion_id;
                                $currency_id = $row2->currency_id;
                                $a_to_b = $row2->a_to_b;

                                $sql = "select sum(mount_value) as mount_value from v_transactions where user_id = ".$puser_id." and
                                 send_date between '".$desde."' and '".$hasta."' and sendstatus <> 'PEN' and conversion_id = ".$conversion_id." and rowstatus = 'ACT'";
                                $sum2 = DB::select($sql);
                                $general_mount_value = $sum2[0]->mount_value;

                                $sql = "select * from currencies where id = ".$currency_id."";
                                $currencies1 = DB::select($sql);
                                $symbol = $currencies1[0]->symbol;
                                $currency = $currencies1[0]->currency;

                                $datos2[] = [
                                    'a_to_b' => $a_to_b,
                                    'general_mount_value' => number_format($general_mount_value,2).$symbol.' '.$currency,
                                ];
                            }
                        }

                        $transactions2 = V_transaction::whereBetween('send_date', [$desde, $hasta])
                              ->where('user_id', $puser_id)
                              ->where('sendstatus', '<>', 'PEN')
                              ->where('rowstatus', 'ACT')
                              ->where('user_id', $puser_id)
                              ->orderBy('id', 'desc')
                              ->simplePaginate(10);
                        $transactions2->withQueryString();

                        $permissions = $this->permissions(1);

                        return view('history.index', compact('permissions', 'transactions', 'transactions2',
                            'desde', 'hasta', 'datos2'));
                        break;
                }

                break;
            case 'see':
                $transaction_id = $request->transaction_id;
                $desde = $request->desde;
                $hasta = $request->hasta;

                $sql = "SELECT * FROM v_transfers where transaction_id = ".$transaction_id." and rowstatus = 'ACT'";
                $transfers = DB::select($sql);

                $sql = "SELECT * FROM v_transactions where id = ".$transaction_id." and rowstatus = 'ACT'";
                $transactions = DB::select($sql);
                $cellphone = $transactions[0]->cellphone;

                $sql = "SELECT phone_code FROM countries WHERE '".$cellphone."' LIKE phone_code || '%'";
                $phonecode = DB::select($sql);
                $phone_code = $phonecode[0]->phone_code;

                $onlycellphone = str_replace($phone_code, '', $cellphone);

                return view('history.see', compact('transactions', 'phone_code',
                    'onlycellphone', 'desde', 'hasta', 'transfers'));
                break;
            case 'see_adm':
                $transfer_id = $request->transfer_id;
                $desde = $request->desde;
                $hasta = $request->hasta;

                $sql = "SELECT * FROM v_transfers where id = ".$transfer_id." and rowstatus = 'ACT'";
                $transfers = DB::select($sql);
                $transaction_id = $transfers[0]->transaction_id;

                $sql = "SELECT * FROM v_transactions where id = ".$transaction_id." and rowstatus = 'ACT'";
                $transactions = DB::select($sql);
                $cellphone = $transactions[0]->cellphone;

                $sql = "SELECT phone_code FROM countries WHERE '".$cellphone."' LIKE phone_code || '%'";
                $phonecode = DB::select($sql);
                $phone_code = $phonecode[0]->phone_code;

                $onlycellphone = str_replace($phone_code, '', $cellphone);

                $prole = auth()->user()->role;

                return view('history.see_adm', compact('transactions', 'phone_code',
                    'onlycellphone', 'desde', 'hasta', 'transfers'));
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

    public function ret_history($desde, $hasta)
    {
        $puser_id = auth()->user()->id;
        $prole = auth()->user()->role;

        switch($prole){
            case 'ADM':
                $sql = "SELECT * FROM v_transactions where send_date between '".$desde."' and '".$hasta."' and sendstatus <> 'PEN' and rowstatus = 'ACT'";
                $transactions = DB::select($sql);

                $transactions2 = V_transaction::whereBetween('send_date', [$desde, $hasta])
                      ->where('sendstatus', '<>', 'PEN')
                      ->where('rowstatus', 'ACT')
                      ->where('user_id', $puser_id)
                      ->orderBy('id', 'desc')
                      ->simplePaginate(10);
                $transactions2->withQueryString();


                break;
            default:
                $sql = "SELECT * FROM v_transactions where user_id = ".$puser_id." and send_date between '".$desde."' and '".$hasta."' and sendstatus <> 'PEN' and rowstatus = 'ACT'";
                $transactions = DB::select($sql);

                $datos2 = [];

                        $sql = "select distinct conversion_id, a_to_b, currency_id from v_transactions where user_id = ".$puser_id." and send_date between '".$desde."' and '".$hasta."' and sendstatus <> 'PEN' and rowstatus = 'ACT'";
                        $transactions_sum = DB::select($sql);

                        if (!empty($transactions_sum) && count($transactions_sum) > 0){
                            foreach ($transactions_sum as $row2){
                                $conversion_id = $row2->conversion_id;
                                $currency_id = $row2->currency_id;
                                $a_to_b = $row2->a_to_b;

                                $sql = "select sum(mount_value) as mount_value from v_transactions where user_id = ".$puser_id." and
                                 send_date between '".$desde."' and '".$hasta."' and sendstatus <> 'PEN' and conversion_id = ".$conversion_id." and rowstatus = 'ACT'";
                                $sum2 = DB::select($sql);
                                $general_mount_value = $sum2[0]->mount_value;

                                $sql = "select * from currencies where id = ".$currency_id."";
                                $currencies1 = DB::select($sql);
                                $symbol = $currencies1[0]->symbol;
                                $currency = $currencies1[0]->currency;

                                $datos2[] = [
                                    'a_to_b' => $a_to_b,
                                    'general_mount_value' => number_format($general_mount_value,2).$symbol.' '.$currency,
                                ];
                            }
                        }

                $transactions2 = V_transaction::whereBetween('send_date', [$desde, $hasta])
                      ->where('user_id', $puser_id)
                      ->where('sendstatus', '<>', 'PEN')
                      ->where('rowstatus', 'ACT')
                      ->where('user_id', $puser_id)
                      ->orderBy('id', 'desc')
                      ->simplePaginate(10);
                $transactions2->withQueryString();

                $permissions = $this->permissions(1);

                return view('history.index', compact('permissions', 'transactions', 'transactions2',
                    'desde', 'hasta', 'datos2'));
                break;
        }


    }
}
