<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WhatsappController extends Controller
{
    public function whatsapp($token)
    {
        /* Ojo , asi se va a hacer el token dekl mensaje por whatsapp
        $firsttoken = bin2hex(random_bytes((10 - (10 % 2)) / 2));
        $thirdtoken = bin2hex(random_bytes((10 - (10 % 2)) / 2));
        $secondtoken = random_int(1000, 9999);
        $fourthtoken = random_int(100, 999);



        $parte1 = $firsttoken."yc";
        $parte2 = "klx".$secondtoken.$thirdtoken.$fourthtoken;
        $nid = 69;
        $texto = $parte1.$nid.$parte2;
        */

        $splitArray = explode('yc', $token);
        $part2 = $splitArray[1];
        $splitArray2 = explode('klx', $part2);
        $transfer_id = $splitArray2[0];

        $sql = "SELECT * FROM v_transfers where id = ".$transfer_id." and rowstatus = 'ACT'";
        $transfers = DB::select($sql);
        $cellphone = $transfers[0]->cellphone;

        $sql = "SELECT phone_code FROM countries WHERE '".$cellphone."' LIKE phone_code || '%'";
        $phonecode = DB::select($sql);
        $phone_code = $phonecode[0]->phone_code;

        $onlycellphone = str_replace($phone_code, '', $cellphone);

        return view('whatsapp.transfer', compact('transfers', 'phone_code', 'onlycellphone'));


    }
}
