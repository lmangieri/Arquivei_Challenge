<?php
namespace App\Service;

use GuzzleHttp\Client;
use App\Invoice;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Queue\EntityNotFoundException;

class InvoicesService {

    public function loadAndSaveInvoicesFromArquivei() {
        $data = $this->loadInvoicesFromArquivei();
        $invoicesArray = $this->convertDataFromArquiveiToArrayOfInvoices($data);
        $loadInvoiceStatus = $this->saveInvoices($invoicesArray);
        return $loadInvoiceStatus;
    }

    public function loadInvoicesFromArquivei(){
        $client = new Client();
        // env('DATABASE_URL')
        $res = $client->request(env('ARQUIVEI_API_method'), env('ARQUIVEI_API_url'), [
            'headers' => ['Content-Type' => env('ARQUIVEI_API_content_type'),
                          'x-api-id' => env('ARQUIVEI_API_id'),
                          'x-api-key' => env('ARQUIVEI_API_key')]
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
        if(!$invoice) {
            throw new EntityNotFoundException('Invoice',$access_key);
        }

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
