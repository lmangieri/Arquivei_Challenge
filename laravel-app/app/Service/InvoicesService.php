<?php
namespace App\Service;

use GuzzleHttp\Client;
use App\Invoice;
use Illuminate\Support\Facades\DB;

class InvoicesService {

    public function loadAndSaveInvoicesFromArquivei() {
        $data = $this->loadInvoicesFromArquivei();
        $invoicesArray = $this->convertDataFromArquiveiToArrayOfInvoices($data);
        $loadInvoiceStatus = $this->saveInvoices($invoicesArray);
        return $loadInvoiceStatus;
    }

    public function loadInvoicesFromArquivei(){
        $client = new Client();
        $res = $client->request('GET', 'https://apiuat.arquivei.com.br/v1/nfe/received', [
            'headers' => ['Content-Type' => 'application/json',
                          'x-api-id' => 'e021f345e68de190b17becb313e81f7874479bcb',
                          'x-api-key' => 'c0d24ab7b6a1732189cabf4d7d4896031c8a25dc']
        ]);

        echo $res->getStatusCode();
        $data = json_decode($res->getBody());
        return $data;
    }

    public function convertDataFromArquiveiToArrayOfInvoices($data) {
        $invoicesArray = array();

        foreach($data->data as $dt) {
            $invoice = new Invoice();
            $invoice->access_key = $dt->access_key;
            $invoice->xml = $dt->xml;
            $invoicesArray[] = $invoice;
        }
        return $invoicesArray;
    }

    public function saveInvoices($arrayOfInvoices) {
        $numberOfInvoicesInserted = 0;
        $numberOfNonInvoicesInserted = 0;

        foreach($arrayOfInvoices as $invoice) {
            try {
                $invoice->save();
                $numberOfInvoicesInserted++;
            } catch (\Illuminate\Database\QueryException $e) {
                $errorCode = $e->errorInfo[1];
                if($errorCode == 1062){
                    $numberOfNonInvoicesInserted++;
                }
            }
        }

        $loadInvoiceStatus = ['numberOfInvoicesInserted' => $numberOfInvoicesInserted,
                             'numberOfNonInvoicesInserted' =>$numberOfNonInvoicesInserted ];
        return $loadInvoiceStatus;
    }

    public function getInvoiceByAccessKey($access_key, $decode) {
        $invoice = DB::table('invoices')->where('access_key',$access_key)->first();

        if ($decode == 'decode') {
            $invoice->xml = base64_decode($invoice->xml);
        }
        return $invoice;
    }

    public function getAccessKeys() {
        $access_key_array = DB::table('invoices')->select('access_key')->get();
        return $access_key_array;
    }
}
