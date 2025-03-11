<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\Buy;
use App\Models\Credit;
use App\Models\Document;
use App\Models\Transaction;
use App\Models\Transfer;
use App\Models\WholesalerPayment;
use App\Models\WholesalerPaymentDetail;
use App\Models\TransferBuy;
use App\Models\V_transfer;
use App\Models\V_user;
use App\Models\V_admtransfers;
use App\Models\V_transaction;
use App\Models\Payer;
use App\Models\TypeDoc;
use App\Models\V_document;
use App\Models\V_wholesaler_payments;
use App\Models\V_wholesaler_payment_details;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class TransactionController extends Controller
{
    private function permissions($case)
    {
        $puser_id = auth()->user()->id;
        $prole = auth()->user()->role;

        switch($case){
            case 1:
                $menu_name = 'PROCESOS';
                $menupopup_name = 'Transacciones';
                break;
            case 2:
                $menu_name = 'REPORTES';
                $menupopup_name = 'Movimientos';
                break;
            case 3:
                $menu_name = 'PROCESOS';
                $menupopup_name = 'Enviadas/Recibidas';
                break;
            case 4:
                $menu_name = 'REPORTES E INDICADORES';
                $menupopup_name = 'Movimientos';
                break;
            case 5:
                $menu_name = 'PROCESOS';
                $menupopup_name = 'En proceso';
                break;
            case 6:
                $menu_name = 'PROCESOS';
                $menupopup_name = 'Pagar a Mayoristas';
                break;
            case 7:
                $menu_name = 'REPORTES';
                $menupopup_name = 'Histórico de Cobros';
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
        $credit = auth()->user()->credit;
        $credit_limit = auth()->user()->credit_limit;

        switch ($prole) {
            case 'ADM':
                session(['submenupopup_id' => 29]);
                session(['menupopup_id' => 20]);
                break;
            case 'ALI':
                session(['menupopup_id' => 14]);
                break;
            case 'USU':
                session(['menupopup_id' => 21]);
                break;
        }

        $permissions = $this->permissions(1);

        $sql = "SELECT * FROM v_conversions where typeuser = '".$prole."' and rowstatus = 'ACT'";
        $conversions = DB::select($sql);

        $sql = "SELECT * FROM v_documents where user_id = ".$puser_id." and favorite = 'Y' and rowstatus = 'ACT'";
        $documents = DB::select($sql);

        $sql = "SELECT coalesce(sum(total_debt), 0) as total_debt FROM v_credits where user_id = ".$puser_id." and creditstatus = 'PEN' and rowstatus = 'ACT'";
        $totalcredits = DB::select($sql);
        if (!empty($totalcredits) && count($totalcredits) > 0){
            $total_debt = $totalcredits[0]->total_debt;
        } else {
            $total_debt = 0.00;
        }

        $sql = "SELECT countryname||' ('||phone_code||')' as country,phone_code FROM countries where rowstatus = 'ACT'";
        $country_phone = DB::select($sql);

        switch ($prole) {
            case 'ADM':
                $permissions = $this->permissions(3);

                $sql = "SELECT * FROM v_transactions where sendstatus <> 'PEN' and sendstatus <> 'PRO' and sendstatus <> 'TRA' and rowstatus = 'ACT'";
                $transactions = DB::select($sql);

                $transactions2 = V_transaction::where('sendstatus', '<>', 'PEN')->where('sendstatus', '<>', 'PRO')->where('sendstatus', '<>', 'TRA')->where('rowstatus', 'ACT')->orderBy('id', 'desc')->simplePaginate(10);
                $transactions2->withQueryString();

                $message = '';
                $message2 = '';
                $payer_cellphone = '';
                $user_cellphone = '';

                return view('transaction.index', compact('permissions', 'transactions',
                'transactions2', 'message', 'payer_cellphone', 'user_cellphone', 'message2'));
                break;
            case 'ALI':
                return view('transaction.new', compact('conversions', 'permissions', 'documents', 'country_phone', 'credit', 'credit_limit', 'total_debt'));
                break;
            case 'USU':
                $sql = "SELECT * FROM payers where user_id = ".$puser_id." and rowstatus = 'ACT'";
                $payers = DB::select($sql);
                $payer_id = $payers[0]->id;
                $payer_name = $payers[0]->payer_name;
                $cellphone = $payers[0]->cellphone;

                $sql = "SELECT phone_code FROM countries WHERE '".$cellphone."' LIKE phone_code || '%'";
                $phonecode = DB::select($sql);
                $phone_code = $phonecode[0]->phone_code;

                $onlycellphone = str_replace($phone_code, '', $cellphone);

                return view('transaction.new_usu', compact('conversions', 'permissions',
                'documents', 'country_phone', 'payer_id','payer_name', 'phone_code','onlycellphone',
                'credit', 'credit_limit', 'total_debt'));
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
            case 'new':
                $pid = auth()->user()->id;
                $prole = auth()->user()->role;
                $credit = auth()->user()->credit;

                $document_id = request('document_id');

                $Document = Document::find($document_id);

                $Document->favorite = request('favorite_value');

                $Document->save();

                $sql = "SELECT id FROM payers where user_id = ".$pid." and payer_name = '".request('payer')."' and cellphone = '".request('totalphone')."' and rowstatus = 'ACT'";
                $payersid = DB::select($sql);

                if (!empty($payersid) && count($payersid) > 0){
                    $payer_id = $payersid[0]->id;
                } else {
                    $Payer = new Payer();

                    $Payer->user_id = $pid;
                    $Payer->payer_name = request('payer');
                    $Payer->cellphone = request('totalphone');

                    $Payer->save();

                    $payer_id = $Payer->id;
                }

                $Transaction = new Transaction();

                $Transaction->user_id = $pid;
                $Transaction->conversion_id = request('conversion_id');
                $Transaction->document_id = request('document_id');
                $Transaction->payer_id = $payer_id;
                $Transaction->mount_value = request('real_mount_value');
                $Transaction->mount_change = request('mount_change2');
                $Transaction->mount_reference = request('mount_reference2');
                $Transaction->amount_withheld = request('real_amount_withheld');
                $Transaction->net_amount = request('net_amount2');
                $Transaction->canawilbank_id = request('canawilbank_id');

                $Transaction->save();

                $transaction_id = $Transaction->id;

                if ($credit == 'Y'){
                    $creditcash = request('credit_value');
                    if ($creditcash == 'N'){
                        $Credit = new Credit();

                        $Credit->transaction_id = $transaction_id;
                        $Credit->total_debt = request('net_amount2');

                        $Credit->save();
                    }
                } else {
                    $creditcash = 'Y';
                }

                $permissions = $this->permissions(1);

                $sql = "SELECT * FROM v_conversions where typeuser = '".$prole."' and rowstatus = 'ACT'";
                $conversions = DB::select($sql);

                $sql = "SELECT * FROM v_documents where user_id = ".$pid." and favorite = 'Y' and rowstatus = 'ACT'";
                $documents = DB::select($sql);

                $sql = "SELECT countryname||' ('||phone_code||')' as country,phone_code FROM countries where rowstatus = 'ACT'";
                $country_phone = DB::select($sql);

                $type_screen = request('type_screen');

                if($type_screen == 'W'){
                    return view('transaction.photoweb', compact('transaction_id', 'credit', 'creditcash'));
                } else {
                    return view('transaction.photo', compact('transaction_id', 'credit', 'creditcash'));
                }
                break;
            case 'photo':
                $transaction_id = request('transaction_id');
                $credit = request('credit');
                $creditcash = request('creditcash');

                $tienefoto = 'N';
                if ($credit == 'N'){
                    $tienefoto = 'Y';
                } else {
                    if ($creditcash = 'Y'){
                        $tienefoto = 'Y';
                    }
                }

                if ($tienefoto == 'Y'){
                    if ($request->hasFile('fileInput')) {
                        // Obtener el archivo subido
                        $file = $request->file('fileInput');

                        // Obtener la extensión del archivo
                        $extension = $file->extension();

                        $filename = '/storage/images/transactions/Image'.$transaction_id.'.'.$extension;

                        $Transaction = Transaction::find($transaction_id);

                        $Transaction->bank_image = $filename;
                        $Transaction->image_orientation = request('orientation');
                        $Transaction->sendstatus = 'ENV';

                        $Transaction->save();

                        $request->file('fileInput')->storeAs('/public/images/transactions/Image'.$transaction_id.'.'.$extension);
                    }
                } else {
                    $filename = '/storage/images/transactions/credit_transaction.png';

                    $Transaction = Transaction::find($transaction_id);

                    $Transaction->bank_image = $filename;
                    $Transaction->image_orientation = 'HOR';
                    $Transaction->sendstatus = 'ENV';

                    $Transaction->save();
                }

                $pid = auth()->user()->id;
                $prole = auth()->user()->role;
                $credit = auth()->user()->credit;
                $credit_limit = auth()->user()->credit_limit;

                $permissions = $this->permissions(1);

                $sql = "SELECT * FROM v_conversions where typeuser = '".$prole."' and rowstatus = 'ACT'";
                $conversions = DB::select($sql);

                $sql = "SELECT * FROM v_documents where user_id = ".$pid." and favorite = 'Y' and rowstatus = 'ACT'";
                $documents = DB::select($sql);

                $sql = "SELECT coalesce(sum(total_debt), 0) as total_debt FROM v_credits where user_id = ".$pid." and creditstatus = 'PEN' and rowstatus = 'ACT'";
                $totalcredits = DB::select($sql);
                if (!empty($totalcredits) && count($totalcredits) > 0){
                    $total_debt = $totalcredits[0]->total_debt;
                } else {
                    $total_debt = 0.00;
                }

                $sql = "SELECT countryname||' ('||phone_code||')' as country,phone_code FROM countries where rowstatus = 'ACT'";
                $country_phone = DB::select($sql);

                switch ($prole) {
                    case 'ALI':
                        session(['menupopup_id' => 14]);
                        return view('transaction.new', compact('conversions', 'permissions', 'documents', 'country_phone', 'credit', 'credit_limit', 'total_debt'));
                        break;
                    case 'USU':
                        session(['menupopup_id' => 21]);

                        $sql = "SELECT * FROM payers where user_id = ".$pid." and rowstatus = 'ACT'";
                        $payers = DB::select($sql);
                        $payer_id = $payers[0]->id;
                        $payer_name = $payers[0]->payer_name;
                        $cellphone = $payers[0]->cellphone;

                        $sql = "SELECT phone_code FROM countries WHERE '".$cellphone."' LIKE phone_code || '%'";
                        $phonecode = DB::select($sql);
                        $phone_code = $phonecode[0]->phone_code;

                        $onlycellphone = str_replace($phone_code, '', $cellphone);
                        return view('transaction.new_usu', compact('conversions', 'permissions',
                        'documents', 'country_phone', 'payer_id','payer_name', 'phone_code','onlycellphone',
                        'credit', 'credit_limit', 'total_debt'));
                        break;
                }
                break;
            case 'creditphoto':
                $transaction_id = request('transaction_id');

                $filename = '/storage/images/transactions/credit_transaction2.png';

                $Transaction = Transaction::find($transaction_id);

                $Transaction->bank_image = $filename;
                $Transaction->image_orientation = 'VER';
                $Transaction->sendstatus = 'ENV';

                $Transaction->save();

                $pid = auth()->user()->id;
                $prole = auth()->user()->role;
                $credit = auth()->user()->credit;
                $credit_limit = auth()->user()->credit_limit;

                $permissions = $this->permissions(1);

                $sql = "SELECT * FROM v_conversions where typeuser = '".$prole."' and rowstatus = 'ACT'";
                $conversions = DB::select($sql);

                $sql = "SELECT * FROM v_documents where user_id = ".$pid." and favorite = 'Y' and rowstatus = 'ACT'";
                $documents = DB::select($sql);

                $sql = "SELECT coalesce(sum(total_debt), 0) as total_debt FROM v_credits where user_id = ".$pid." and creditstatus = 'PEN' and rowstatus = 'ACT'";
                $totalcredits = DB::select($sql);
                if (!empty($totalcredits) && count($totalcredits) > 0){
                    $total_debt = $totalcredits[0]->total_debt;
                } else {
                    $total_debt = 0.00;
                }

                $sql = "SELECT countryname||' ('||phone_code||')' as country,phone_code FROM countries where rowstatus = 'ACT'";
                $country_phone = DB::select($sql);

                switch ($prole) {
                    case 'ALI':
                        session(['menupopup_id' => 14]);
                        return view('transaction.new', compact('conversions', 'permissions', 'documents', 'country_phone', 'credit', 'credit_limit', 'total_debt'));
                        break;
                    case 'USU':
                        session(['menupopup_id' => 21]);

                        $sql = "SELECT * FROM payers where user_id = ".$pid." and rowstatus = 'ACT'";
                        $payers = DB::select($sql);
                        $payer_id = $payers[0]->id;
                        $payer_name = $payers[0]->payer_name;
                        $cellphone = $payers[0]->cellphone;

                        $sql = "SELECT phone_code FROM countries WHERE '".$cellphone."' LIKE phone_code || '%'";
                        $phonecode = DB::select($sql);
                        $phone_code = $phonecode[0]->phone_code;

                        $onlycellphone = str_replace($phone_code, '', $cellphone);
                        return view('transaction.new_usu', compact('conversions', 'permissions',
                        'documents', 'country_phone', 'payer_id','payer_name', 'phone_code','onlycellphone',
                        'credit', 'credit_limit', 'total_debt'));
                        break;
                }
                break;
            case 'photoweb':
                $transaction_id = request('transaction_id');
                $credit = request('credit');
                $creditcash = request('creditcash');

                $tienefoto = 'N';
                if ($credit == 'N'){
                    $tienefoto = 'Y';
                } else {
                    if ($creditcash == 'Y'){
                        $tienefoto = 'Y';
                    }
                }

                if ($tienefoto == 'Y'){
                    if ($request->has('imageData')){
                        // Obtener el dato de la imagen
                        $imageData  = $request->input('imageData');

                        // Decodificar la imagen base64 (remover la parte inicial de la cadena de base64)
                        $imageParts = explode(";base64,", $imageData);
                        $imageTypeAux = explode("image/", $imageParts[0]);
                        $imageType = $imageTypeAux[1]; // Obtener la extensión (png, jpg, etc.)
                        $imageBase64 = base64_decode($imageParts[1]);

                        $filename = '/storage/images/transactions/Image'.$transaction_id.'.'.$imageType;

                        // Guardar la imagen en el almacenamiento
                        Storage::put('public/images/transactions/Image'.$transaction_id.'.'.$imageType, $imageBase64);

                        $Transaction = Transaction::find($transaction_id);

                        $Transaction->bank_image = $filename;
                        $Transaction->image_orientation = request('orientation');
                        $Transaction->sendstatus = 'ENV';

                        $Transaction->save();
                    }
                } else {
                    $filename = '/storage/images/transactions/credit_transaction.png';

                    $Transaction = Transaction::find($transaction_id);

                    $Transaction->bank_image = $filename;
                    $Transaction->image_orientation = 'HOR';
                    $Transaction->sendstatus = 'ENV';

                    $Transaction->save();
                }

                $pid = auth()->user()->id;
                $prole = auth()->user()->role;
                $credit = auth()->user()->credit;
                $credit_limit = auth()->user()->credit_limit;

                $permissions = $this->permissions(1);

                $sql = "SELECT * FROM v_conversions where typeuser = '".$prole."' and rowstatus = 'ACT'";
                $conversions = DB::select($sql);

                $sql = "SELECT * FROM v_documents where user_id = ".$pid." and favorite = 'Y' and rowstatus = 'ACT'";
                $documents = DB::select($sql);

                $sql = "SELECT coalesce(sum(total_debt), 0) as total_debt FROM v_credits where user_id = ".$pid." and creditstatus = 'PEN' and rowstatus = 'ACT'";
                $totalcredits = DB::select($sql);
                if (!empty($totalcredits) && count($totalcredits) > 0){
                    $total_debt = $totalcredits[0]->total_debt;
                } else {
                    $total_debt = 0.00;
                }

                $sql = "SELECT countryname||' ('||phone_code||')' as country,phone_code FROM countries where rowstatus = 'ACT'";
                $country_phone = DB::select($sql);

                switch ($prole) {
                    case 'ALI':
                        session(['menupopup_id' => 14]);
                        return view('transaction.new', compact('conversions', 'permissions', 'documents', 'country_phone', 'credit', 'credit_limit', 'total_debt'));
                        break;
                    case 'USU':
                        session(['menupopup_id' => 21]);

                        $sql = "SELECT * FROM payers where user_id = ".$pid." and rowstatus = 'ACT'";
                        $payers = DB::select($sql);
                        $payer_id = $payers[0]->id;
                        $payer_name = $payers[0]->payer_name;
                        $cellphone = $payers[0]->cellphone;

                        $sql = "SELECT phone_code FROM countries WHERE '".$cellphone."' LIKE phone_code || '%'";
                        $phonecode = DB::select($sql);
                        $phone_code = $phonecode[0]->phone_code;

                        $onlycellphone = str_replace($phone_code, '', $cellphone);
                        return view('transaction.new_usu', compact('conversions', 'permissions',
                        'documents', 'country_phone', 'payer_id','payer_name', 'phone_code','onlycellphone',
                        'credit', 'credit_limit', 'total_debt'));
                        break;
                }
                break;
            case 'creditphotoweb':
                    $transaction_id = request('transaction_id');

                    $filename = '/storage/images/transactions/credit_transaction.png';

                    $Transaction = Transaction::find($transaction_id);

                    $Transaction->bank_image = $filename;
                    $Transaction->image_orientation = 'HOR';
                    $Transaction->sendstatus = 'ENV';

                    $Transaction->save();

                    $pid = auth()->user()->id;
                    $prole = auth()->user()->role;
                    $credit = auth()->user()->credit;
                    $credit_limit = auth()->user()->credit_limit;

                    $permissions = $this->permissions(1);

                    $sql = "SELECT * FROM v_conversions where typeuser = '".$prole."' and rowstatus = 'ACT'";
                    $conversions = DB::select($sql);

                    $sql = "SELECT * FROM v_documents where user_id = ".$pid." and favorite = 'Y' and rowstatus = 'ACT'";
                    $documents = DB::select($sql);

                    $sql = "SELECT coalesce(sum(total_debt), 0) as total_debt FROM v_credits where user_id = ".$pid." and creditstatus = 'PEN' and rowstatus = 'ACT'";
                    $totalcredits = DB::select($sql);
                    if (!empty($totalcredits) && count($totalcredits) > 0){
                        $total_debt = $totalcredits[0]->total_debt;
                    } else {
                        $total_debt = 0.00;
                    }

                    $sql = "SELECT countryname||' ('||phone_code||')' as country,phone_code FROM countries where rowstatus = 'ACT'";
                    $country_phone = DB::select($sql);

                    switch ($prole) {
                        case 'ALI':
                            session(['menupopup_id' => 14]);
                            return view('transaction.new', compact('conversions', 'permissions', 'documents', 'country_phone', 'credit', 'credit_limit', 'total_debt'));
                            break;
                        case 'USU':
                            session(['menupopup_id' => 21]);

                            $sql = "SELECT * FROM payers where user_id = ".$pid." and rowstatus = 'ACT'";
                            $payers = DB::select($sql);
                            $payer_id = $payers[0]->id;
                            $payer_name = $payers[0]->payer_name;
                            $cellphone = $payers[0]->cellphone;

                            $sql = "SELECT phone_code FROM countries WHERE '".$cellphone."' LIKE phone_code || '%'";
                            $phonecode = DB::select($sql);
                            $phone_code = $phonecode[0]->phone_code;

                            $onlycellphone = str_replace($phone_code, '', $cellphone);
                            return view('transaction.new_usu', compact('conversions', 'permissions',
                            'documents', 'country_phone', 'payer_id','payer_name', 'phone_code','onlycellphone',
                            'credit', 'credit_limit', 'total_debt'));
                            break;
                    }
                    break;
            case 'new_usu':
                $pid = auth()->user()->id;
                $prole = auth()->user()->role;
                $credit = auth()->user()->credit;

                $document_id = request('document_id');
                $payer_id = request('payer_id');

                $Document = Document::find($document_id);

                $Document->favorite = request('favorite_value');

                $Document->save();

                $Transaction = new Transaction();

                $Transaction->user_id = $pid;
                $Transaction->conversion_id = request('conversion_id');
                $Transaction->document_id = request('document_id');
                $Transaction->payer_id = $payer_id;
                $Transaction->mount_value = request('real_mount_value');
                $Transaction->mount_change = request('mount_change2');
                $Transaction->mount_reference = request('mount_reference2');
                $Transaction->amount_withheld = request('real_amount_withheld');
                $Transaction->net_amount = request('net_amount2');
                $Transaction->canawilbank_id = request('canawilbank_id');

                $Transaction->save();

                $transaction_id = $Transaction->id;

                if ($credit == 'Y'){
                    $creditcash = request('credit_value');
                    if ($creditcash == 'N'){
                        $Credit = new Credit();

                        $Credit->transaction_id = $transaction_id;
                        $Credit->total_debt = request('net_amount2');

                        $Credit->save();
                    }
                } else {
                    $creditcash = 'Y';
                }

                $permissions = $this->permissions(3);

                $sql = "SELECT * FROM v_conversions where typeuser = '".$prole."' and rowstatus = 'ACT'";
                $conversions = DB::select($sql);

                $sql = "SELECT * FROM v_documents where user_id = ".$pid." and favorite = 'Y' and rowstatus = 'ACT'";
                $documents = DB::select($sql);

                $sql = "SELECT countryname||' ('||phone_code||')' as country,phone_code FROM countries where rowstatus = 'ACT'";
                $country_phone = DB::select($sql);

                $type_screen = request('type_screen');

                if($type_screen == 'W'){
                    return view('transaction.photoweb', compact('transaction_id', 'credit', 'creditcash'));
                } else {
                    return view('transaction.photo', compact('transaction_id', 'credit', 'creditcash'));
                }
                break;
            case 'transfer':
                $transaction_id = $request->transaction_id;
                $type_screen = $request->type_screen;

                $Transaction = Transaction::find($transaction_id);

                $Transaction->sendstatus = 'REC';

                $Transaction->save();

                $sql = "SELECT * FROM v_transactions where id = ".$transaction_id." and rowstatus = 'ACT'";
                $transactions = DB::select($sql);
                $cellphone = $transactions[0]->cellphone;
                $country_id = $transactions[0]->country2_id;
                $mount_change = $transactions[0]->mount_change;

                $sql = "SELECT * FROM v_currency_banks where country_id = ".$country_id." and rowstatus = 'ACT'";
                $currency_banks = DB::select($sql);

                $sql = "SELECT phone_code FROM countries WHERE '".$cellphone."' LIKE phone_code || '%'";
                $phonecode = DB::select($sql);
                $phone_code = $phonecode[0]->phone_code;

                $onlycellphone = str_replace($phone_code, '', $cellphone);

                $sql = "SELECT * FROM v_transfers where transaction_id = ".$transaction_id." and rowstatus = 'ACT'";
                $transfers = DB::select($sql);

                $sql = "SELECT sum(amount) as amount FROM v_transferbuys where transaction_id = ".$transaction_id." and rowstatus = 'ACT'";
                $transferbuys = DB::select($sql);

                $amount_rest = 0;
                foreach($transferbuys as $row3){
                    $amount_rest = $row3->amount;
                }

                $sumamount_rest = $mount_change - $amount_rest;

                $origin = 'transaction';

                if ($type_screen == 'W'){
                    return view('transaction.transfer', compact('transactions', 'phone_code', 'origin',
                    'onlycellphone', 'currency_banks', 'transfers', 'amount_rest', 'sumamount_rest'));
                } else {
                    return view('transaction.mobtransfer', compact('transactions', 'phone_code', 'origin',
                    'onlycellphone', 'currency_banks', 'transfers', 'amount_rest', 'sumamount_rest'));
                }
                break;
            case 'save_transfer_web':
                $transaction_id = $request->transaction_id;
                $amount_rest = $request->amount_rest;
                $real_mount_change = $request->real_mount_change;
                $real_amount = $request->real_amount;

                $defamount = $real_amount + $amount_rest;

                if ($real_mount_change > $defamount){
                    $sendstatus = 'PRO';
                } else {
                    $sendstatus = 'TRA';
                }

                $Transaction = Transaction::find($transaction_id);

                $Transaction->sendstatus = $sendstatus;

                $Transaction->save();

                if ($request->has('imageData')){
                    $sql = "select max(id) as id from transfers where rowstatus = 'ACT'";
                    $transfersid = DB::select($sql);

                    $newtransfer_id = 0;
                    foreach ($transfersid as $row){
                        $newtransfer_id = $row->id;
                    }
                    $newtransfer_id++;

                    // Obtener el dato de la imagen
                    $imageData  = $request->input('imageData');

                    // Decodificar la imagen base64 (remover la parte inicial de la cadena de base64)
                    $imageParts = explode(";base64,", $imageData);
                    $imageTypeAux = explode("image/", $imageParts[0]);
                    $imageType = $imageTypeAux[1]; // Obtener la extensión (png, jpg, etc.)
                    $imageBase64 = base64_decode($imageParts[1]);

                    $filename = '/storage/images/transfer/Image'.$newtransfer_id.'.'.$imageType;

                    // Guardar la imagen en el almacenamiento
                    Storage::put('public/images/transfer/Image'.$newtransfer_id.'.'.$imageType, $imageBase64);

                    $Transfer = new Transfer();

                    $Transfer->transaction_id = $transaction_id;
                    $Transfer->currencybank_id = request('currencybank_id');
                    $Transfer->bank_image = $filename;
                    $Transfer->image_orientation = request('orientation');
                    $Transfer->amount = $real_amount;
                    $Transfer->transfer_date = Carbon::now();

                    $Transfer->save();

                    $transfer_id = $Transfer->id;

                    $flag = true;

                    while ($flag) {
                        $currencybank_id = request('currencybank_id');
                        $sql = "select id, available_amount from buys where currencybank_id=".$currencybank_id."";
                        $buys = DB::select($sql);

                        $lastKey = end($buys)->id;

                        foreach ($buys as $row2) {
                            $available_amount = $row2->available_amount;
                            $buy_id = $row2->id;
                            if ($available_amount > 0){
                                if ($available_amount > $real_amount){
                                    $netamount = $available_amount - $real_amount;

                                    $TransferBuy = new TransferBuy();

                                    $TransferBuy->transfer_id = $transfer_id;
                                    $TransferBuy->buy_id = $buy_id;
                                    $TransferBuy->amount = $real_amount;

                                    $TransferBuy->save();

                                    $Buy = Buy::find($buy_id);

                                    $Buy->available_amount = $netamount;

                                    $Buy->save();

                                    $flag = false; // Cambiamos el flag para salir del ciclo
                                } else {
                                    $TransferBuy = new TransferBuy();

                                    $TransferBuy->transfer_id = $transfer_id;
                                    $TransferBuy->buy_id = $buy_id;
                                    $TransferBuy->amount = $available_amount;

                                    $TransferBuy->save();

                                    $Buy = Buy::find($buy_id);

                                    $Buy->available_amount = 0.00;

                                    $Buy->save();

                                    $real_amount = $real_amount - $available_amount;
                                }
                            } else {
                                if ($buy_id == $lastKey) {
                                    $flag = false; // es el ultimo registro
                                }
                            }
                        }
                    }
                }

                if ($sendstatus == 'PRO'){
                    $sql = "SELECT * FROM v_transactions where id = ".$transaction_id." and rowstatus = 'ACT'";
                    $transactions = DB::select($sql);
                    $cellphone = $transactions[0]->cellphone;
                    $country_id = $transactions[0]->country2_id;
                    $mount_change = $transactions[0]->mount_change;

                    $sql = "SELECT * FROM v_currency_banks where country_id = ".$country_id." and rowstatus = 'ACT'";
                    $currency_banks = DB::select($sql);

                    $sql = "SELECT phone_code FROM countries WHERE '".$cellphone."' LIKE phone_code || '%'";
                    $phonecode = DB::select($sql);
                    $phone_code = $phonecode[0]->phone_code;

                    $onlycellphone = str_replace($phone_code, '', $cellphone);

                    $sql = "SELECT * FROM v_transfers where transaction_id = ".$transaction_id." and rowstatus = 'ACT'";
                    $transfers = DB::select($sql);

                    $sql = "SELECT sum(amount) as amount FROM v_transferbuys where transaction_id = ".$transaction_id." and rowstatus = 'ACT'";
                    $transferbuys = DB::select($sql);

                    $amount_rest = 0;
                    foreach($transferbuys as $row3){
                        $amount_rest = $row3->amount;
                    }

                    $sumamount_rest = $mount_change - $amount_rest;

                    $origin = 'transaction';

                    $type_screen = $request->type_screen;

                    if ($type_screen == 'W'){
                        return view('transaction.transfer', compact('transactions', 'phone_code', 'origin',
                        'onlycellphone', 'currency_banks', 'transfers', 'amount_rest', 'sumamount_rest'));
                    } else {
                        return view('transaction.mobtransfer', compact('transactions', 'phone_code', 'origin',
                        'onlycellphone', 'currency_banks', 'transfers', 'amount_rest', 'sumamount_rest'));
                    }
                } else {
                    $sql = "SELECT * FROM v_transactions where id = ".$transaction_id." and rowstatus = 'ACT'";
                    $transaction = DB::select($sql);

                    $comercial_name = $transaction[0]->comercial_name;
                    $payer_name = $transaction[0]->payer_name;
                    $payer_cellphone = $transaction[0]->cellphone;
                    $formatted_date = Carbon::now()->format('d-m-Y');
                    $a_to_b = $transaction[0]->a_to_b;
                    $mount_value_fm = trim($transaction[0]->mount_value_fm);
                    $symbol = $transaction[0]->symbol;
                    $currency = $transaction[0]->currency;
                    $role = $transaction[0]->role;
                    $conversion_value = $transaction[0]->conversion_value;
                    $formatted_value = number_format($conversion_value, 2);
                    $user_cellphone = $transaction[0]->user_cellphone;
                    $currency2 = $transaction[0]->currency2;
                    $mount_change_fm = trim($transaction[0]->mount_change_fm);
                    $symbol2 = $transaction[0]->symbol2;

                    if ($role == 'ALI'){
                        $cliente = 'Aliado Comercial';
                    } else {
                        $cliente = 'Usuario';
                    }

                    $firsttoken = bin2hex(random_bytes((10 - (10 % 2)) / 2));
                    $thirdtoken = bin2hex(random_bytes((10 - (10 % 2)) / 2));
                    $secondtoken = random_int(1000, 9999);
                    $fourthtoken = random_int(100, 999);

                    $parte1 = $firsttoken."yc";
                    $parte2 = "klx".$secondtoken.$thirdtoken.$fourthtoken;
                    $nid = $transaction_id;
                    $texto = $parte1.$nid.$parte2;

                    $urlrecibo = env('URL_APP').'/v1/whatsapp/'.$texto;

                    $message = "Saludos *".$payer_name."*, la App *Cambios CANAWIL* te informa que hemos realizado "
                        . "exitosamente la transferencia por ti solicitada con la siguente información detallada:\n\n"
                        . "*".$cliente.":* ".$comercial_name."\n"
                        . "*ID:* ".$transfer_id."\n"
                        . "*Fecha:* ".$formatted_date."\n"
                        . "*Tipo de Cambio:* ".$a_to_b."\n"
                        . "*Monto a Cambiar:* ".$mount_value_fm.$symbol.' '.$currency."\n"
                        . "*Tasa de Cambio:* ".$formatted_value.$symbol.' '.$currency."\n"
                        . "*Monto a Pagar:* ".$mount_change_fm.$symbol2.' '.$currency2."\n\n"
                        . "Los demas detalles de la transacción puede verlo accediendo al link que le dejamos a continuación:\n\n"
                        . $urlrecibo."\n\n";

                    $message2 = "Srs. *".$comercial_name."*, la App *Cambios CANAWIL* te informa que hemos realizado "
                        . "exitosamente la transferencia enviada por Uds. con la siguente información detallada:\n\n"
                        . "*".$cliente.":* ".$comercial_name."\n"
                        . "*ID:* ".$transfer_id."\n"
                        . "*Fecha:* ".$formatted_date."\n"
                        . "*Tipo de Cambio:* ".$a_to_b."\n"
                        . "*Monto a Cambiar:* ".$mount_value_fm.$symbol.' '.$currency."\n"
                        . "*Tasa de Cambio:* ".$formatted_value.$symbol.' '.$currency."\n"
                        . "*Monto a Pagar:* ".$mount_change_fm.$symbol2.' '.$currency2."\n\n"
                        . "Los demas detalles de la transacción puede verlo accediendo al link que le dejamos a continuación:\n\n"
                        . $urlrecibo."\n\n";

                    $origin = $request->origin;
                    if ($origin == 'transaction'){
                        session(['submenupopup_id' => 29]);
                        session(['menupopup_id' => 20]);

                        $permissions = $this->permissions(3);

                        $sql = "SELECT * FROM v_transactions where sendstatus <> 'PEN' and sendstatus <> 'PRO' and sendstatus <> 'TRA' and rowstatus = 'ACT'";
                        $transactions = DB::select($sql);

                        $transactions2 = V_transaction::where('sendstatus', '<>', 'PEN')->where('sendstatus', '<>', 'PRO')->where('sendstatus', '<>', 'TRA')->where('rowstatus', 'ACT')->orderBy('id', 'desc')->simplePaginate(10);
                        $transactions2->withQueryString();

                        return view('transaction.index', compact('permissions', 'transactions',
                        'transactions2', 'message', 'payer_cellphone', 'user_cellphone', 'message2'));
                    } else {
                        session(['submenupopup_id' => 29]);
                        session(['menupopup_id' => 28]);

                        $permissions = $this->permissions(5);

                        $sql = "SELECT * FROM v_transactions where sendstatus = 'PRO' and rowstatus = 'ACT'";
                        $transfers = DB::select($sql);

                        $transfers2 = V_transaction::where('sendstatus', 'PRO')->where('rowstatus', 'ACT')->orderBy('id', 'desc')->simplePaginate(10);
                        $transfers2->withQueryString();

                        return view('transfer.index', compact('permissions', 'transfers', 'transfers2',
                                'message', 'payer_cellphone', 'message2', 'user_cellphone'));
                    }
                }
                break;
            case 'photo_trans':
                $transaction_id = $request->transaction_id;
                $amount_rest = $request->amount_rest;
                $real_mount_change = $request->real_mount_change;
                $real_amount = $request->real_amount;

                $defamount = $real_amount + $amount_rest;

                if ($real_mount_change > $defamount){
                    $sendstatus = 'PRO';
                } else {
                    $sendstatus = 'TRA';
                }

                $Transaction = Transaction::find($transaction_id);

                $Transaction->sendstatus = $sendstatus;

                $Transaction->save();

                if ($request->hasFile('fileInput')){
                    // Obtener el archivo subido
                    $file = $request->file('fileInput');

                    // Obtener la extensión del archivo
                    $extension = $file->extension();

                    $sql = "select max(id) as id from transfers where rowstatus = 'ACT'";
                    $transfersid = DB::select($sql);

                    $newtransfer_id = 0;
                    foreach ($transfersid as $row){
                        $newtransfer_id = $row->id;
                    }
                    $newtransfer_id++;

                    $filename = '/storage/images/transfer/Image'.$newtransfer_id.'.'.$extension;

                    $request->file('fileInput')->storeAs('/public/images/transfer/Image'.$newtransfer_id.'.'.$extension);

                    $Transfer = new Transfer();

                    $Transfer->transaction_id = $transaction_id;
                    $Transfer->currencybank_id = request('currencybank_id');
                    $Transfer->bank_image = $filename;
                    $Transfer->image_orientation = request('orientation');
                    $Transfer->amount = $real_amount;
                    $Transfer->transfer_date = Carbon::now();

                    $Transfer->save();

                    $transfer_id = $Transfer->id;

                    $flag = true;

                    while ($flag) {
                        $currencybank_id = request('currencybank_id');
                        $sql = "select id, available_amount from buys where currencybank_id=".$currencybank_id."";
                        $buys = DB::select($sql);

                        $lastKey = end($buys)->id;

                        foreach ($buys as $row2) {
                            $available_amount = $row2->available_amount;
                            $buy_id = $row2->id;
                            if ($available_amount > 0){
                                if ($available_amount > $real_amount){
                                    $netamount = $available_amount - $real_amount;

                                    $TransferBuy = new TransferBuy();

                                    $TransferBuy->transfer_id = $transfer_id;
                                    $TransferBuy->buy_id = $buy_id;
                                    $TransferBuy->amount = $real_amount;

                                    $TransferBuy->save();

                                    $Buy = Buy::find($buy_id);

                                    $Buy->available_amount = $netamount;

                                    $Buy->save();

                                    $flag = false; // Cambiamos el flag para salir del ciclo
                                } else {
                                    $TransferBuy = new TransferBuy();

                                    $TransferBuy->transfer_id = $transfer_id;
                                    $TransferBuy->buy_id = $buy_id;
                                    $TransferBuy->amount = $available_amount;

                                    $TransferBuy->save();

                                    $Buy = Buy::find($buy_id);

                                    $Buy->available_amount = 0.00;

                                    $Buy->save();

                                    $real_amount = $real_amount - $available_amount;
                                }
                            } else {
                                if ($buy_id == $lastKey) {
                                    $flag = false; // es el ultimo registro
                                }
                            }
                        }
                    }
                }

                if ($sendstatus == 'PRO'){
                    $sql = "SELECT * FROM v_transactions where id = ".$transaction_id." and rowstatus = 'ACT'";
                    $transactions = DB::select($sql);
                    $cellphone = $transactions[0]->cellphone;
                    $country_id = $transactions[0]->country2_id;
                    $mount_change = $transactions[0]->mount_change;

                    $sql = "SELECT * FROM v_currency_banks where country_id = ".$country_id." and rowstatus = 'ACT'";
                    $currency_banks = DB::select($sql);

                    $sql = "SELECT phone_code FROM countries WHERE '".$cellphone."' LIKE phone_code || '%'";
                    $phonecode = DB::select($sql);
                    $phone_code = $phonecode[0]->phone_code;

                    $onlycellphone = str_replace($phone_code, '', $cellphone);

                    $sql = "SELECT * FROM v_transfers where transaction_id = ".$transaction_id." and rowstatus = 'ACT'";
                    $transfers = DB::select($sql);

                    $sql = "SELECT sum(amount) as amount FROM v_transferbuys where transaction_id = ".$transaction_id." and rowstatus = 'ACT'";
                    $transferbuys = DB::select($sql);

                    $amount_rest = 0;
                    foreach($transferbuys as $row3){
                        $amount_rest = $row3->amount;
                    }

                    $sumamount_rest = $mount_change - $amount_rest;

                    $origin = 'transaction';

                    $type_screen = $request->type_screen;

                    if ($type_screen == 'W'){
                        return view('transaction.transfer', compact('transactions', 'phone_code', 'origin',
                        'onlycellphone', 'currency_banks', 'transfers', 'amount_rest', 'sumamount_rest'));
                    } else {
                        return view('transaction.mobtransfer', compact('transactions', 'phone_code', 'origin',
                        'onlycellphone', 'currency_banks', 'transfers', 'amount_rest', 'sumamount_rest'));
                    }
                } else {
                    $sql = "SELECT * FROM v_transactions where id = ".$transaction_id." and rowstatus = 'ACT'";
                    $transaction = DB::select($sql);

                    $comercial_name = $transaction[0]->comercial_name;
                    $payer_name = $transaction[0]->payer_name;
                    $payer_cellphone = $transaction[0]->cellphone;
                    $formatted_date = Carbon::now()->format('d-m-Y');
                    $a_to_b = $transaction[0]->a_to_b;
                    $mount_value_fm = trim($transaction[0]->mount_value_fm);
                    $symbol = $transaction[0]->symbol;
                    $currency = $transaction[0]->currency;
                    $role = $transaction[0]->role;
                    $conversion_value = $transaction[0]->conversion_value;
                    $formatted_value = number_format($conversion_value, 2);
                    $user_cellphone = $transaction[0]->user_cellphone;
                    $currency2 = $transaction[0]->currency2;
                    $mount_change_fm = trim($transaction[0]->mount_change_fm);
                    $symbol2 = $transaction[0]->symbol2;

                    if ($role == 'ALI'){
                        $cliente = 'Aliado Comercial';
                    } else {
                        $cliente = 'Usuario';
                    }

                    $firsttoken = bin2hex(random_bytes((10 - (10 % 2)) / 2));
                    $thirdtoken = bin2hex(random_bytes((10 - (10 % 2)) / 2));
                    $secondtoken = random_int(1000, 9999);
                    $fourthtoken = random_int(100, 999);

                    $parte1 = $firsttoken."yc";
                    $parte2 = "klx".$secondtoken.$thirdtoken.$fourthtoken;
                    $nid = $transaction_id;
                    $texto = $parte1.$nid.$parte2;

                    $urlrecibo = env('URL_APP').'/v1/whatsapp/'.$texto;

                    $message = "Saludos *".$payer_name."*, la App *Cambios CANAWIL* te informa que hemos realizado "
                        . "exitosamente la transferencia por ti solicitada con la siguente información detallada:\n\n"
                        . "*".$cliente.":* ".$comercial_name."\n"
                        . "*ID:* ".$transfer_id."\n"
                        . "*Fecha:* ".$formatted_date."\n"
                        . "*Tipo de Cambio:* ".$a_to_b."\n"
                        . "*Monto a Cambiar:* ".$mount_value_fm.$symbol.' '.$currency."\n"
                        . "*Tasa de Cambio:* ".$formatted_value.$symbol.' '.$currency."\n"
                        . "*Monto a Pagar:* ".$mount_change_fm.$symbol2.' '.$currency2."\n\n"
                        . "Los demas detalles de la transacción puede verlo accediendo al link que le dejamos a continuación:\n\n"
                        . $urlrecibo."\n\n";

                    $message2 = "Srs. *".$comercial_name."*, la App *Cambios CANAWIL* te informa que hemos realizado "
                        . "exitosamente la transferencia enviada por Uds. con la siguente información detallada:\n\n"
                        . "*".$cliente.":* ".$comercial_name."\n"
                        . "*ID:* ".$transfer_id."\n"
                        . "*Fecha:* ".$formatted_date."\n"
                        . "*Tipo de Cambio:* ".$a_to_b."\n"
                        . "*Monto a Cambiar:* ".$mount_value_fm.$symbol.' '.$currency."\n"
                        . "*Tasa de Cambio:* ".$formatted_value.$symbol.' '.$currency."\n"
                        . "*Monto a Pagar:* ".$mount_change_fm.$symbol2.' '.$currency2."\n\n"
                        . "Los demas detalles de la transacción puede verlo accediendo al link que le dejamos a continuación:\n\n"
                        . $urlrecibo."\n\n";

                    $origin = $request->origin;
                    if ($origin == 'transaction'){
                        session(['submenupopup_id' => 29]);
                        session(['menupopup_id' => 20]);

                        $permissions = $this->permissions(3);

                        $sql = "SELECT * FROM v_transactions where sendstatus <> 'PEN' and sendstatus <> 'PRO' and sendstatus <> 'TRA' and rowstatus = 'ACT'";
                        $transactions = DB::select($sql);

                        $transactions2 = V_transaction::where('sendstatus', '<>', 'PEN')->where('sendstatus', '<>', 'PRO')->where('sendstatus', '<>', 'TRA')->where('rowstatus', 'ACT')->orderBy('id', 'desc')->simplePaginate(10);
                        $transactions2->withQueryString();

                        return view('transaction.index', compact('permissions', 'transactions',
                        'transactions2', 'message', 'payer_cellphone', 'user_cellphone', 'message2'));
                    } else {
                        session(['submenupopup_id' => 29]);
                        session(['menupopup_id' => 28]);

                        $permissions = $this->permissions(5);

                        $sql = "SELECT * FROM v_transactions where sendstatus = 'PRO' and rowstatus = 'ACT'";
                        $transfers = DB::select($sql);

                        $transfers2 = V_transaction::where('sendstatus', 'PRO')->where('rowstatus', 'ACT')->orderBy('id', 'desc')->simplePaginate(10);
                        $transfers2->withQueryString();

                        return view('transfer.index', compact('permissions', 'transfers', 'transfers2',
                                'message', 'payer_cellphone', 'message2', 'user_cellphone'));
                    }
                }
                break;
            case 'wholesaler':
                $wholesaler_id = $request->wholesaler_id;

                $sql = "SELECT * FROM v_users where id = ".$wholesaler_id." and rowstatus = 'ACT'";
                $users = DB::select($sql);

                // Construye la consulta SQL en múltiples líneas
                $sql = "
                SELECT *
                FROM v_admtransfers
                WHERE user_id IN (
                    SELECT DISTINCT affiliate_id
                    FROM affiliates
                    WHERE wholesaler_id = ?
                )
                AND transaction_id NOT IN (
                    SELECT DISTINCT transaction_id
                    FROM wholesaler_payment_details
                )
                ";
                // Ejecuta la consulta utilizando el método DB::select con bound parameters
                $admtransfers = DB::select($sql, [$wholesaler_id]);

                $sql = "
                SELECT sum(wholesaler_amount) as sum_wholesaler_payment
                FROM v_admtransfers
                WHERE user_id IN (
                    SELECT DISTINCT affiliate_id
                    FROM affiliates
                    WHERE wholesaler_id = ?
                )
                AND transaction_id NOT IN (
                    SELECT DISTINCT transaction_id
                    FROM wholesaler_payment_details
                )
                ";
                // Ejecuta la consulta utilizando el método DB::select con bound parameters
                $sumwholesaler = DB::select($sql, [$wholesaler_id]);

                $mount_sumwholesaler = 0.00;
                foreach($sumwholesaler as $row){
                    $mount_sumwholesaler = $row->sum_wholesaler_payment;
                }

                return view('transaction.wholesaler', compact('users', 'admtransfers', 'mount_sumwholesaler'));
                break;
            case 'wholesaler_pay':
                $wholesaler_id = request('wholesaler_id');
                $currency_id = request('currency_id');
                $total_amount = request('total_amount');
                $real_payment_date = request('real_payment_date');

                // Captura el valor del campo 'selected_ids' desde el request
                $selectedIdsString = $request->input('selected_ids');

                // Convierte la cadena separada por comas en un array
                if ($selectedIdsString) {
                    $selectedIds = explode(',', $selectedIdsString);
                } else {
                    $selectedIds = [];
                }

                $WholesalerPayment = new WholesalerPayment();

                $WholesalerPayment->wholesaler_id = $wholesaler_id;
                $WholesalerPayment->currency_id = $currency_id;
                $WholesalerPayment->date = $real_payment_date;
                $WholesalerPayment->amount = $total_amount;

                $WholesalerPayment->save();

                $wholesalerpayment_id = $WholesalerPayment->id;

                foreach ($selectedIds as $selectedId) {
                    // Aquí $selectedId contiene cada ID del array $selectedIds
                    $transferbuy_id = $selectedId;

                    $sql = "SELECT transaction_id FROM v_transferbuys where id = ".$transferbuy_id." and rowstatus = 'ACT'";
                    $transfer_buys = DB::select($sql);
                    $transaction_id = $transfer_buys[0]->transaction_id;

                    $sql = "SELECT id FROM wholesaler_payment_details where wholesalerpayment_id = ".$wholesalerpayment_id." and transaction_id = ".$transaction_id." and rowstatus = 'ACT'";
                    $wholesaler_payment_details_id = DB::select($sql);
                    $id = 0;
                    foreach($wholesaler_payment_details_id as $row2){
                        $id = $row2->id;
                    }

                    if ($id == 0){
                        $WholesalerPaymentDetail = new WholesalerPaymentDetail();

                        $WholesalerPaymentDetail->wholesalerpayment_id = $wholesalerpayment_id;
                        $WholesalerPaymentDetail->transaction_id = $transaction_id;

                        $WholesalerPaymentDetail->save();
                    }
                }

                $permissions = $this->permissions(6);

                // Obtener todos los empleados de esa compañia con status activo de la BD
                $sql = "SELECT * FROM v_users where role ='MAY' and rowstatus = 'ACT'";
                $users = DB::select($sql);

                $users2 = V_user::where('role', 'MAY')->where('rowstatus', 'ACT')->orderBy('name', 'asc')->simplePaginate(10);
                $users2->withQueryString();

                return view('transaction.indexwholesaler', compact('users', 'users2', 'permissions'));
                break;
            case 'wholesalerhistory':
                $wholesalerpayment_id = request('wholesalerpayment_id');

                $sql = "SELECT * FROM v_wholesaler_payment_details where wholesalerpayment_id = ".$wholesalerpayment_id." and rowstatus = 'ACT'";
                $wholesaler_payment_details = DB::select($sql);

                $wholesaler_payment_details2 = V_wholesaler_payment_details::where('wholesalerpayment_id', $wholesalerpayment_id)->where('rowstatus', 'ACT')->orderBy('affilieate_comercial_name', 'asc')->simplePaginate(10);
                $wholesaler_payment_details2->withQueryString();

                return view('transaction.wholesaler_paydetail', compact('wholesaler_payment_details', 'wholesaler_payment_details2'));
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

    public function getway($id)
    {
        $sql = "select * from way_to_pays where country_id='".$id."' order by description";
        $way_to_pays = DB::select($sql);

        return $way_to_pays;
    }

    public function description($id)
    {
        $sql = "select * from v_conversions where id='".$id."'";
        $conversions = DB::select($sql);

        $conversion_description = $conversions[0]->conversion_description;
        $conversion_value = $conversions[0]->conversion_value;
        $reference_conversion_value = $conversions[0]->reference_conversion_value;
        $currency_description = $conversions[0]->currency_description;
        $currency_description2 = $conversions[0]->currency_description2;
        $currency_description3 = $conversions[0]->currency_description3;
        $currency = $conversions[0]->currency;
        $currency2 = $conversions[0]->currency2;
        $currency3 = $conversions[0]->currency3;
        $symbol = $conversions[0]->symbol;
        $symbol2 = $conversions[0]->symbol2;
        $symbol3 = $conversions[0]->symbol3;
        $country_id = $conversions[0]->country_id;
        $country2_id = $conversions[0]->country2_id;
        $two_decimals = $conversions[0]->two_decimals;
        $commission = $conversions[0]->customer_commission;

        $sql = "select * from canawil_banks where country_id='".$country_id."' order by bank_name";
        $canawil_banks = DB::select($sql);

        $puser_id = auth()->user()->id;
        $country_id = auth()->user()->country_id;
        $sql = "select * from v_users where id='".$puser_id."' and rowstatus = 'ACT'";
        $users = DB::select($sql);
        $role_name = $users[0]->role_name;

        $sql = "select * from way_to_pays where country_id='".$country_id."' order by description";
        $way_to_pays = DB::select($sql);

        $sql = "select phone_code from countries where id='".$country2_id."'";
        $countries = DB::select($sql);
        $phone_code = $countries[0]->phone_code;

        $datos = [];

        $datos['conversion_description'] = $conversion_description;
        $datos['conversion_value'] = $conversion_value;
        $datos['reference_conversion_value'] = $reference_conversion_value;
        $datos['currency_description'] = $currency_description;
        $datos['currency_description2'] = $currency_description2;
        $datos['currency_description3'] = $currency_description3;
        $datos['currency'] = $currency;
        $datos['currency2'] = $currency2;
        $datos['currency3'] = $currency3;
        $datos['symbol'] = $symbol;
        $datos['symbol2'] = $symbol2;
        $datos['symbol3'] = $symbol3;
        $datos['country2_id'] = $country2_id;
        $datos['phone_code'] = $phone_code;
        $datos['two_decimals'] = $two_decimals;
        $datos['canawil_banks'] = $canawil_banks;
        $datos['way_to_pays'] = $way_to_pays;
        $datos['commission'] = $commission;
        $datos['role_name'] = $role_name;

        return $datos;
    }

    public function typedoc2($id)
    {
        return TypeDoc::where('country_id', $id)->where('rowstatus', 'ACT')->orderBy('description', 'asc')->get();
    }

    public function bank2($id)
    {
        return Bank::where('country_id', $id)->where('rowstatus', 'ACT')->orderBy('bankname', 'asc')->get();
    }

    public function findfavorite($id)
    {
        return V_document::where('id', $id)->get();
    }

    public function findpayer($id)
    {
        $sql = "select * from payers where id='".$id."'";
        $payers = DB::select($sql);
        $payer_name = $payers[0]->payer_name;
        $cellphone = $payers[0]->cellphone;

        $sql = "SELECT phone_code FROM countries WHERE '".$cellphone."' LIKE phone_code || '%'";
        $phonecode = DB::select($sql);
        $phone_code = $phonecode[0]->phone_code;

        $number_without_code = str_replace($phone_code, '', $cellphone);

        $datos = [];

        $datos['payer_name'] = $payer_name;
        $datos['phone_code'] = $phone_code;
        $datos['onlynumber'] = $number_without_code;

        return $datos;
    }

    public function createdoc(Request $request)
    {
        $puser_id = auth()->user()->id;

        $existingRecord = Document::where('account_number', $request->input('account_number'))->first();

        if ($existingRecord) {
            // Si el registro ya existe, devuelve un mensaje
            return response()->json([
                'status' => 'error',
                'data' => null,
            ]);
        } else {
            // Si el registro no existe, lo guardamos
            $newRecord = new Document;

            $newRecord->user_id = $puser_id;
            $newRecord->typedoc_id = $request->input('typedoc_id');
            $newRecord->numdoc = $request->input('numdoc');
            $newRecord->account_holder = $request->input('account_holder');
            $newRecord->bank_id = $request->input('bank_id');
            $newRecord->account_number = $request->input('account_number');

            $newRecord->save();

            // Ahora hacemos una consulta a la vista
            $result = V_document::where('id', $newRecord->id)->get();

            return response()->json([
                'status' => 'success',
                'data' => $result,
            ]);
        }
    }

    public function serial($id)
    {
        $sql = "select prefix from banks where id=".$id."";
        $bank = DB::select($sql);

        $prefix = '';
        foreach ($bank as $row2) {
            $prefix = $row2->prefix;
        }

        $datos = [];

        $datos['prefix'] = $prefix;

        return $datos;
    }

    public function account($id)
    {
        $sql = "select account_number, description from v_canawil_banks where id=".$id."";
        $canawil_banks = DB::select($sql);

        $account_number = '';
        foreach ($canawil_banks as $row2) {
            $account_number = $row2->account_number;
            $description = $row2->description;
        }

        $datos = [];

        $datos['account_number'] = $account_number;
        $datos['description'] = $description;

        return $datos;
    }

    public function account2($id)
    {
        $sql = "select * from v_currency_banks where id=".$id."";
        $currency_banks = DB::select($sql);

        $account_number = '';
        $currency = '';
        $symbol = '';
        $currency_id = 0;
        foreach ($currency_banks as $row2) {
            $account_number = $row2->account_number;
            $currency = $row2->currency;
            $symbol = $row2->symbol;
            $currency_id = $row2->currency_id;
        }

        $datos = [];

        $datos['account_number'] = $account_number;
        $datos['currency'] = $currency;
        $datos['symbol'] = $symbol;
        $datos['currency_id'] = $currency_id;

        return $datos;
    }

    public function way($id)
    {
        $sql = "select reference_text from v_way_to_pays where id=".$id."";
        $way_to_pays = DB::select($sql);

        $reference_text = '';
        foreach ($way_to_pays as $row2) {
            $reference_text = $row2->reference_text;
        }

        $datos = [];

        $datos['reference'] = $reference_text;

        return $datos;
    }

    public function available($id)
    {
        $sql = "select sum(available_amount) as available_amount from buys where currencybank_id=".$id." and rowstatus = 'ACT'";
        $buys = DB::select($sql);

        $available_amount = 0.00;
        foreach ($buys as $row2) {
            $available_amount = $row2->available_amount;
        }

        $datos = [];

        $datos['available_amount'] = number_format($available_amount,2,',','.');
        $datos['real_available_amount'] = $available_amount;

        return $datos;
    }

    public function findnumcuenta($numcuenta)
    {
        $puser_id = auth()->user()->id;

        $existingRecord = Document::where('user_id', $puser_id)->where('account_number', $numcuenta)->first();

        if ($existingRecord) {
            // Ahora hacemos una consulta a la vista
            $result = V_document::where('account_number', $numcuenta)->get();

            return response()->json([
                'status' => 'success',
                'data' => $result,
            ]);
        } else {
            // Si el registro no existe, devuelve un mensaje
            return response()->json([
                'status' => 'error',
                'data' => null,
            ]);
        }
    }

    public function findnumdoc($numdoc)
    {
        $puser_id = auth()->user()->id;

        $sql = "select * from v_documents where user_id = ".$puser_id." and numdoc = '".$numdoc."'";
        $data = DB::select($sql);

        return $data;
    }

    public function findnombre($nombre)
    {
        $puser_id = auth()->user()->id;

        $sql = "select * from v_documents where user_id = ".$puser_id." and account_holder ILIKE '%".$nombre."%'";
        $data = DB::select($sql);

        return $data;
    }

    public function findnombrepayer()
    {
        $puser_id = auth()->user()->id;

        $sql = "select * from payers where user_id = ".$puser_id." and rowstatus = 'ACT'";
        $data = DB::select($sql);

        return $data;
    }

    public function delete_payer($id)
    {
        $Payer = Payer::find($id);

        $Payer->rowstatus = 'INA';

        $Payer->save();

        $datos = [];

        $datos['id'] = $id;

        return $datos;
    }

    public function daily()
    {
        $puser_id = auth()->user()->id;
        $prole = auth()->user()->role;
        switch ($prole) {
            case 'ADM':
                session(['menupopup_id' => 30]);
                $permissions = $this->permissions(4);
                break;
            case 'ALI':
                session(['menupopup_id' => 15]);
                $permissions = $this->permissions(2);
                break;
            case 'USU':
                session(['menupopup_id' => 25]);
                $permissions = $this->permissions(2);
                break;
        }

        switch ($prole){
            case 'ADM':
                $sql = "select * from v_transfers where transfer_date = date(now()) and sendstatus = 'TRA' and rowstatus = 'ACT'";
                $transfers = DB::select($sql);

                $transfers2 = V_transfer::where('transfer_date', date('Y-m-d'))->where('sendstatus', 'TRA')->where('rowstatus', 'ACT')->orderBy('id', 'desc')->simplePaginate(10);
                $transfers2->withQueryString();

                $datos = [];
                $datos2 = [];

                $sql = "select distinct conversion_id from v_transfers where transfer_date = date(now()) and sendstatus = 'TRA' and rowstatus = 'ACT'";
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

                        $sql = "select sum(net_amount2) as mount_value, sum(transfer_amount) as mount_change from v_transfers
                        where transfer_date = date(now()) and conversion_id = ".$conversion_id." and sendstatus = 'TRA' and rowstatus = 'ACT'";
                        $sum = DB::select($sql);
                        $total_mount_value = $sum[0]->mount_value;
                        $total_mount_change = $sum[0]->mount_change;

                        $datos[] = [
                            'typeuser_char' => $typeuser_char,
                            'divisa1' => $currency_description,
                            'mount_value' => $total_mount_value,
                            'total_mount_value' => number_format($total_mount_value,2,',','.').$symbol.' '.$currency,
                            'divisa2' => $currency_description2,
                            'mount_change' => $total_mount_change,
                            'total_mount_change' => number_format($total_mount_change,2,',','.').$symbol2.' '.$currency2
                        ];
                    }
                }

                $sql = "select distinct a_to_b, currency_id, currency2_id from v_transfers where transfer_date = date(now()) and sendstatus = 'TRA' and rowstatus = 'ACT'";
                $transfers_sum = DB::select($sql);

                if (!empty($transfers_sum) && count($transfers_sum) > 0){
                    foreach ($transfers_sum as $row3){
                        $a_to_b = $row3->a_to_b;
                        $currency_id = $row3->currency_id;
                        $currency2_id = $row3->currency2_id;

                        $sql = "select sum(net_amount2) as mount_value, sum(transfer_amount) as mount_change from v_transfers
                        where transfer_date = date(now()) and a_to_b = '".$a_to_b."' and sendstatus = 'TRA' and rowstatus = 'ACT'";
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

                        $sql = "select sum(canawil_amount_withheld) as canawil_amount_withheld from v_admtransfers
                        where transfer_date = date(now()) and a_to_b = '".$a_to_b."' and rowstatus = 'ACT'";
                        $sum3 = DB::select($sql);
                        $general_canawil_amount_withheld = $sum3[0]->canawil_amount_withheld;

                        $datos2[] = [
                            'a_to_b' => $a_to_b,
                            'general_mount_value' => number_format($general_mount_value,2,',','.').$symbol.' '.$currency,
                            'general_mount_change' => number_format($general_mount_change,2,',','.').$symbol2.' '.$currency2,
                            'general_canawil_amount_withheld' => number_format($general_canawil_amount_withheld,2,',','.').$symbol.' '.$currency
                        ];
                    }
                }

                return view('transfer.daily', compact('permissions', 'transfers', 'transfers2',
                'datos', 'datos2'));
                break;
            default:
                $sql = "select * from v_transactions where user_id = ".$puser_id." and send_date = date(now()) and sendstatus <> 'PEN' and rowstatus = 'ACT'";
                $transactions = DB::select($sql);

                $datos2 = [];

                $sql = "select distinct conversion_id, a_to_b, currency_id from v_transactions where user_id = ".$puser_id." and send_date = date(now()) and sendstatus <> 'PEN' and rowstatus = 'ACT'";
                $transactions_sum = DB::select($sql);

                if (!empty($transactions_sum) && count($transactions_sum) > 0){
                    foreach ($transactions_sum as $row2){
                        $conversion_id = $row2->conversion_id;
                        $currency_id = $row2->currency_id;
                        $a_to_b = $row2->a_to_b;

                        $sql = "select sum(net_amount) as send_amount, sum(mount_value) as mount_value, sum(amount_withheld) as amount_withheld
                        from v_transactions where user_id = ".$puser_id." and send_date = date(now()) and sendstatus <> 'PEN'
                        and conversion_id = ".$conversion_id." and rowstatus = 'ACT'";
                        $sum2 = DB::select($sql);
                        $general_send_amount = $sum2[0]->send_amount;
                        $general_mount_value = $sum2[0]->mount_value;
                        $general_amount_withheld = $sum2[0]->amount_withheld;

                        $sql = "select * from currencies where id = ".$currency_id."";
                        $currencies1 = DB::select($sql);
                        $symbol = $currencies1[0]->symbol;
                        $currency = $currencies1[0]->currency;

                        $datos2[] = [
                            'a_to_b' => $a_to_b,
                            'general_send_amount' => number_format($general_send_amount,2,',','.').$symbol.' '.$currency,
                            'general_mount_value' => number_format($general_mount_value,2,',','.').$symbol.' '.$currency,
                            'general_amount_withheld' => number_format($general_amount_withheld,2,',','.').$symbol.' '.$currency,
                        ];
                    }
                }

                $transactions2 = V_transaction::where('user_id', $puser_id)->where('send_date', date('Y-m-d'))->where('sendstatus', '<>', 'PEN')->where('rowstatus', 'ACT')->orderBy('id', 'desc')->simplePaginate(10);
                $transactions2->withQueryString();

                return view('transaction.daily', compact('permissions', 'transactions', 'transactions2',
                'datos2'));
                break;
        }
    }

    public function wholesaler_payment()
    {
        $prole = auth()->user()->role;
        switch ($prole) {
            case 'ADM':
                session(['menupopup_id' => 48]);
                break;
        }

        $permissions = $this->permissions(6);

        // Obtener todos los empleados de esa compañia con status activo de la BD
        $sql = "SELECT * FROM v_users where role ='MAY' and rowstatus = 'ACT'";
        $users = DB::select($sql);

        $users2 = V_user::where('role', 'MAY')->where('rowstatus', 'ACT')->orderBy('name', 'asc')->simplePaginate(10);
        $users2->withQueryString();

        return view('transaction.indexwholesaler', compact('users', 'users2', 'permissions'));
    }

    public function wholesaler_report()
    {
        $puser_id = auth()->user()->id;
        $prole = auth()->user()->role;

        switch ($prole) {
            case 'MAY':
                session(['menupopup_id' => 45]);
                break;
        }

        $permissions = $this->permissions(7);

        // Obtener todos los empleados de esa compañia con status activo de la BD
        $sql = "SELECT * FROM v_wholesaler_payments where wholesaler_id = ".$puser_id." and rowstatus = 'ACT'";
        $wholesaler_payments = DB::select($sql);

        $wholesaler_payments2 = V_wholesaler_payments::where('wholesaler_id', $puser_id)->where('rowstatus', 'ACT')->orderBy('date', 'desc')->simplePaginate(10);
        $wholesaler_payments2->withQueryString();

        return view('transaction.indexwholesalerpayment', compact('wholesaler_payments', 'wholesaler_payments2', 'permissions'));
    }
}
