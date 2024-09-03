<?php

namespace App\Http\Controllers;

use App\Models\Transfer;
use App\Models\V_transfer;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TransferController extends Controller
{
    private function permissions($case)
    {
        $puser_id = auth()->user()->id;
        $prole = auth()->user()->role;

        switch($case){
            case 1:
                $menu_name = 'PROCESOS';
                $menupopup_name = 'En proceso';
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
        //
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
            case 'save_transfer':
                $transfer_id = $request->transfer_id;

                $Transfer = V_transfer::find($transfer_id);

                $transaction_id = $Transfer->transaction_id;
                $payer_cellphone = $Transfer->cellphone;

                $type_screen = request('type_screen');

                return view('transfer.photo_trans', compact('transaction_id', 'transfer_id',
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

                $sql = "SELECT * FROM v_transfers where id = ".$transfer_id." and rowstatus = 'ACT'";
                $transaction = DB::select($sql);

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

                $permissions = $this->permissions(1);

                $sql = "SELECT * FROM v_transfers where sendstatus = 'PRO' and rowstatus = 'ACT'";
                $transfers = DB::select($sql);

                $transfers2 = V_transfer::where('sendstatus', 'PRO')->where('rowstatus', 'ACT')->orderBy('id', 'desc')->simplePaginate(10);
                $transfers2->withQueryString();

                return view('transfer.index', compact('permissions', 'transfers', 'transfers2',
                'message', 'payer_cellphone', 'message2', 'user_cellphone'));
                break;
            case 'see':
                $transfer_id = $request->transfer_id;

                $sql = "SELECT * FROM v_transfers where id = ".$transfer_id." and rowstatus = 'ACT'";
                $transfers = DB::select($sql);
                $cellphone = $transfers[0]->cellphone;

                $sql = "SELECT phone_code FROM countries WHERE '".$cellphone."' LIKE phone_code || '%'";
                $phonecode = DB::select($sql);
                $phone_code = $phonecode[0]->phone_code;

                $onlycellphone = str_replace($phone_code, '', $cellphone);

                return view('transfer.see', compact('transfers', 'phone_code', 'onlycellphone'));
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

    public function proccess()
    {
        session(['submenupopup_id' => 29]);
        session(['menupopup_id' => 28]);

        $permissions = $this->permissions(1);

        $sql = "SELECT * FROM v_transfers where sendstatus = 'PRO' and rowstatus = 'ACT'";
        $transfers = DB::select($sql);

        $transfers2 = V_transfer::where('sendstatus', 'PRO')->where('rowstatus', 'ACT')->orderBy('id', 'desc')->simplePaginate(10);
        $transfers2->withQueryString();

        $message = '';
        $message2 = '';
        $payer_cellphone = '';
        $user_cellphone = '';

        return view('transfer.index', compact('permissions', 'transfers', 'transfers2',
                'message', 'payer_cellphone', 'message2', 'user_cellphone'));
    }
}
