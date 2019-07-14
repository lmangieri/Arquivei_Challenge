<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use GuzzleHttp\Client;
use App\Invoice;

class InvoiceController extends Controller
{
    public function index() {
        return view('home');
    }

    public function loadInvoicesFromArquivei() {
        $client = new Client();
        $res = $client->request('GET', 'https://apiuat.arquivei.com.br/v1/nfe/received', [
            'headers' => ['Content-Type' => 'application/json',
                          'x-api-id' => 'e021f345e68de190b17becb313e81f7874479bcb',
                          'x-api-key' => 'c0d24ab7b6a1732189cabf4d7d4896031c8a25dc']
        ]);

        echo $res->getStatusCode();
        $data = json_decode($res->getBody());

        echo $data->status->code;

        $invoices = array();

        $numberOfInvoicesInserted = 0;
        $numberOfNonInvoicesInserted = 0;

        foreach($data->data as $dt) {
            $invoice = new Invoice();
            $invoice->access_key = $dt->access_key;
            $invoice->xml = $dt->xml;
            try {
                $invoice->save();
                echo 'Success :) <br>';
                $numberOfInvoicesInserted++;
            } catch (\Illuminate\Database\QueryException $e) {
                $errorCode = $e->errorInfo[1];
                echo 'error code ' .$errorCode . '<br>';
                if($errorCode == 1062){
                    $numberOfNonInvoicesInserted++;
                }
            }
        }

        echo 'success ' . $numberOfInvoicesInserted.' <br>';
        echo 'alreadyExist ' . $numberOfNonInvoicesInserted.' <br>';

        $loadInvoiceStatus = ['numberOfInvoicesInserted' => $numberOfInvoicesInserted,
                             'numberOfNonInvoicesInserted' =>$numberOfNonInvoicesInserted ];

        return redirect('/')->with('loadInvoiceStatus',$loadInvoiceStatus);
    }

    public function getAccessKeys() {
        $access_key_array = DB::table('invoices')->select('access_key')->get();

        return redirect('/')->with('access_key_array',$access_key_array);
    }
}