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

        $sql = "SELECT * FROM v_transactions where id = ".$transfer_id." and rowstatus = 'ACT'";
        $transfers = DB::select($sql);
        $cellphone = $transfers[0]->cellphone;

        $sql = "SELECT * FROM v_transfers where transaction_id = ".$transfer_id." and rowstatus = 'ACT'";
        $transfers2 = DB::select($sql);

        $sql = "SELECT phone_code FROM countries WHERE '".$cellphone."' LIKE phone_code || '%'";
        $phonecode = DB::select($sql);
        $phone_code = $phonecode[0]->phone_code;

        $onlycellphone = str_replace($phone_code, '', $cellphone);

        return view('whatsapp.transfer', compact('transfers', 'transfers2', 'phone_code',
        'onlycellphone'));
    }

    public function shopify($order)
    {
        header('Access-Control-Allow-Origin: http://localhost:8001');
        header('Content-Type: application/json');

        $shopify_access_token = getenv('SHOPIFY_ACCESS_TOKEN');

        // Initialize cURL
        $ch = curl_init();

        //https://30081f-72.myshopify.com/admin/api/2024-10/graphql.json

        // Configure cURL options
        // curl_setopt($ch, CURLOPT_URL, "https://30081f-72.myshopify.com/admin/api/2024-10/orders/" . $order . ".json");
        curl_setopt($ch, CURLOPT_URL, "https://30081f-72.myshopify.com/admin/api/2024-10/graphql.json");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'X-Shopify-Access-Token: ' . $shopify_access_token
        ));

        // Execute cURL request
    $response = curl_exec($ch);

    // Check if any errors occurred during the request
    if (curl_errno($ch)) {
        echo 'Curl error: ' . curl_error($ch);
        curl_close($ch);
        return;  // Terminate the function if there is an error
    }

    // Close cURL resource
    curl_close($ch);

    // Decode JSON to a PHP array
    $data = json_decode($response, true);

    // Setting headers to return JSON
    header('Content-Type: application/json');

    // Return data in JSON format
    echo json_encode($data);

    }
}
