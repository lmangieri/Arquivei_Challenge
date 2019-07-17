<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Service\InvoicesService;
use Illuminate\Contracts\Queue\EntityNotFoundException;

class InvoiceController extends Controller
{
    private $invoiceService;

    public function __construct(InvoicesService $invoiceService) {
        $this->invoiceService = $invoiceService;
    }

    public function index() {
        return view('home');
    }

    public function loadInvoicesFromArquivei() {
        $loadInvoiceStatus = $this->invoiceService->loadAndSaveInvoicesFromArquivei();
        return redirect('/')->with('loadInvoiceStatus',$loadInvoiceStatus);
    }

    public function getAccessKeys() {
        $access_key_array = $this->invoiceService->getAccessKeys();
        return redirect('/')->with('access_key_array',$access_key_array);
    }

    public function getInvoiceByAccessKey(Request $request) {
        $access_key = $request->input('access_key');
        $decode = $request->input('decode');
        try {
            $invoice = $this->invoiceService->getInvoiceByAccessKey($access_key, $decode);
        } catch (EntityNotFoundException $e) {
             return redirect('/')->with('invoiceNotFound','$access_key');
        }



        return redirect('/')->with('invoice',$invoice);
    }
}
