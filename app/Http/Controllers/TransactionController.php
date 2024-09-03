<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\Document;
use App\Models\Transaction;
use App\Models\Transfer;
use App\Models\V_transfer;
use App\Models\V_transaction;
use App\Models\Payer;
use App\Models\TypeDoc;
use App\Models\V_document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
                return view('transaction.new', compact('conversions', 'permissions', 'documents', 'country_phone'));
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
                'documents', 'country_phone', 'payer_id','payer_name', 'phone_code','onlycellphone'));
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
                $Transaction->canawilbank_id = request('canawilbank_id');
                $Transaction->waytopay_id = request('waytopay_id');
                $Transaction->waytopay_reference = request('waytopay_reference') ?? '';

                $Transaction->save();

                $transaction_id = $Transaction->id;

                $permissions = $this->permissions(1);

                $sql = "SELECT * FROM v_conversions where typeuser = '".$prole."' and rowstatus = 'ACT'";
                $conversions = DB::select($sql);

                $sql = "SELECT * FROM v_documents where user_id = ".$pid." and favorite = 'Y' and rowstatus = 'ACT'";
                $documents = DB::select($sql);

                $sql = "SELECT countryname||' ('||phone_code||')' as country,phone_code FROM countries where rowstatus = 'ACT'";
                $country_phone = DB::select($sql);

                $type_screen = request('type_screen');

                return view('transaction.photo', compact('transaction_id'));
                break;
            case 'photo':
                $transaction_id = request('transaction_id');

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

                $pid = auth()->user()->id;
                $prole = auth()->user()->role;

                $permissions = $this->permissions(1);

                $sql = "SELECT * FROM v_conversions where typeuser = '".$prole."' and rowstatus = 'ACT'";
                $conversions = DB::select($sql);

                $sql = "SELECT * FROM v_documents where user_id = ".$pid." and favorite = 'Y' and rowstatus = 'ACT'";
                $documents = DB::select($sql);

                $sql = "SELECT countryname||' ('||phone_code||')' as country,phone_code FROM countries where rowstatus = 'ACT'";
                $country_phone = DB::select($sql);

                switch ($prole) {
                    case 'ALI':
                        session(['menupopup_id' => 14]);
                        return view('transaction.new', compact('conversions', 'permissions', 'documents', 'country_phone'));
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
                        'documents', 'country_phone', 'payer_id','payer_name', 'phone_code','onlycellphone'));
                        break;
                }
                break;
            case 'new_usu':
                $pid = auth()->user()->id;
                $prole = auth()->user()->role;

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
                $Transaction->canawilbank_id = request('canawilbank_id');
                $Transaction->waytopay_id = request('waytopay_id');
                $Transaction->waytopay_reference = request('waytopay_reference') ?? '';

                $Transaction->save();

                $transaction_id = $Transaction->id;

                $permissions = $this->permissions(3);

                $sql = "SELECT * FROM v_conversions where typeuser = '".$prole."' and rowstatus = 'ACT'";
                $conversions = DB::select($sql);

                $sql = "SELECT * FROM v_documents where user_id = ".$pid." and favorite = 'Y' and rowstatus = 'ACT'";
                $documents = DB::select($sql);

                $sql = "SELECT countryname||' ('||phone_code||')' as country,phone_code FROM countries where rowstatus = 'ACT'";
                $country_phone = DB::select($sql);

                $type_screen = request('type_screen');

                return view('transaction.photo', compact('transaction_id'));
                break;
            case 'transfer':
                $transaction_id = $request->transaction_id;

                $Transaction = Transaction::find($transaction_id);

                $Transaction->sendstatus = 'REC';

                $Transaction->save();

                $country_id = auth()->user()->country_id;

                $sql = "SELECT * FROM canawil_banks where country_id = ".$country_id." and rowstatus = 'ACT'";
                $canawil_banks = DB::select($sql);

                $sql = "SELECT * FROM way_to_pays where country_id = ".$country_id." and rowstatus = 'ACT'";
                $way_to_pays = DB::select($sql);

                $sql = "SELECT * FROM v_transactions where id = ".$transaction_id." and rowstatus = 'ACT'";
                $transactions = DB::select($sql);
                $cellphone = $transactions[0]->cellphone;

                $sql = "SELECT phone_code FROM countries WHERE '".$cellphone."' LIKE phone_code || '%'";
                $phonecode = DB::select($sql);
                $phone_code = $phonecode[0]->phone_code;

                $onlycellphone = str_replace($phone_code, '', $cellphone);

                return view('transaction.transfer', compact('transactions', 'phone_code',
                'onlycellphone', 'canawil_banks', 'way_to_pays'));
                break;
            case 'save_transfer':
                $prole = auth()->user()->role;

                $transaction_id = $request->transaction_id;
                $payer_cellphone = $request->payer_cellphone;

                $Transaction = Transaction::find($transaction_id);

                $Transaction->sendstatus = 'PRO';

                $Transaction->save();

                $Transfer = new Transfer();

                $Transfer->transaction_id = $transaction_id;
                $Transfer->canawilbank_id = request('canawilbank_id');
                $Transfer->waytopay_id = request('waytopay_id');
                $Transfer->waytopay_id = request('waytopay_id');
                $Transfer->waytopay_reference = request('waytopay_reference') ?? '';

                $Transfer->save();

                $transfer_id = $Transfer->id;

                $type_screen = request('type_screen');

                $prole = auth()->user()->role;
                switch ($prole) {
                    case 'ADM':
                        session(['menupopup_id' => 20]);
                        break;
                }

                return view('transaction.photo_trans', compact('transaction_id', 'transfer_id',
                        'payer_cellphone'));
                break;
            case 'photo_trans':
                $transaction_id = request('transaction_id');
                $transfer_id = request('transfer_id');
                $payer_cellphone = request('payer_cellphone');

                if ($request->hasFile('fileInput')) {
                    // Obtener el archivo subido
                    $file = $request->file('fileInput');

                    // Obtener la extensión del archivo
                    $extension = $file->extension();

                    $filename = '/storage/images/transfer/Image'.$transfer_id.'.'.$extension;

                    $Transaction = Transaction::find($transaction_id);

                    $Transaction->sendstatus = 'TRA';

                    $Transaction->save();

                    $Transfer = Transfer::find($transfer_id);

                    $Transfer->bank_image = $filename;
                    $Transfer->image_orientation = request('orientation');

                    $Transfer->save();

                    $request->file('fileInput')->storeAs('/public/images/transfer/Image'.$transfer_id.'.'.$extension);
                }

                $sql = "SELECT * FROM v_transfers where transaction_id = ".$transaction_id." and rowstatus = 'ACT'";
                $transaction = DB::select($sql);

                $transfer_id = $transaction[0]->id;
                $comercial_name = $transaction[0]->comercial_name;
                $payer_name = $transaction[0]->payer_name;
                $transfer_date = $transaction[0]->transfer_date;
                $formatted_date = Carbon::parse($transfer_date)->format('d-m-Y');
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
                $currency = $transaction[0]->currency;

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
                $nid = $transfer_id;
                $texto = $parte1.$nid.$parte2;

                $urlrecibo = env('URL_APP').'/v1/whatsapp/'.$texto;

                $message = "Saludos *".$payer_name."*, la App *Canawil Cambios* te informa que hemos realizado "
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

                $message2 = "Srs. *".$comercial_name."*, la App *Canawil Cambios* te informa que hemos realizado "
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

                $permissions = $this->permissions(3);

                $sql = "SELECT * FROM v_transactions where sendstatus <> 'PEN' and sendstatus <> 'PRO' and sendstatus <> 'TRA' and rowstatus = 'ACT'";
                $transactions = DB::select($sql);

                $transactions2 = V_transaction::where('sendstatus', '<>', 'PEN')->where('sendstatus', '<>', 'PRO')->where('sendstatus', '<>', 'TRA')->where('rowstatus', 'ACT')->orderBy('id', 'desc')->simplePaginate(10);
                $transactions2->withQueryString();

                return view('transaction.index', compact('permissions', 'transactions',
                'transactions2', 'message', 'payer_cellphone', 'user_cellphone', 'message2'));
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

        $sql = "select * from canawil_banks where country_id='".$country_id."' order by bank_name";
        $canawil_banks = DB::select($sql);

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
        $sql = "select account_number from canawil_banks where id=".$id."";
        $canawil_banks = DB::select($sql);

        $account_number = '';
        foreach ($canawil_banks as $row2) {
            $account_number = $row2->account_number;
        }

        $datos = [];

        $datos['account_number'] = $account_number;

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

                $sql = "select distinct conversion_id from v_transfers where transfer_date = date(now()) and rowstatus = 'ACT'";
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
                        where transfer_date = date(now()) and conversion_id = ".$conversion_id." and rowstatus = 'ACT'";
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

                $sql = "select distinct a_to_b, currency_id, currency2_id from v_transfers where transfer_date = date(now()) and rowstatus = 'ACT'";
                $transfers_sum = DB::select($sql);

                if (!empty($transfers_sum) && count($transfers_sum) > 0){
                    foreach ($transfers_sum as $row3){
                        $a_to_b = $row3->a_to_b;
                        $currency_id = $row3->currency_id;
                        $currency2_id = $row3->currency2_id;

                        $sql = "select sum(mount_value) as mount_value, sum(mount_change) as mount_change from v_transfers
                        where transfer_date = date(now()) and a_to_b = '".$a_to_b."' and rowstatus = 'ACT'";
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

                        $sql = "select sum(mount_value) as mount_value from v_transactions where user_id = ".$puser_id." and
                         send_date = date(now()) and sendstatus <> 'PEN' and conversion_id = ".$conversion_id." and rowstatus = 'ACT'";
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

                $transactions2 = V_transaction::where('user_id', $puser_id)->where('send_date', date('Y-m-d'))->where('sendstatus', '<>', 'PEN')->where('rowstatus', 'ACT')->orderBy('id', 'desc')->simplePaginate(10);
                $transactions2->withQueryString();

                return view('transaction.daily', compact('permissions', 'transactions', 'transactions2',
                'datos2'));
                break;
        }
    }
}
