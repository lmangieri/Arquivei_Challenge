<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Service\InvoicesService;
use Illuminate\Contracts\Queue\EntityNotFoundException;

class ApiInvoicesController extends Controller
{
    private $invoiceService;

    public function __construct(InvoicesService $invoiceService) {
        $this->invoiceService = $invoiceService;
    }

    public function loadInvoicesFromArquivei(Request $request) {
        $loadInvoiceStatus = $this->invoiceService->loadAndSaveInvoicesFromArquivei();
        return response()->json($loadInvoiceStatus,201);
    }

    public function getAccessKeys() {
        $access_key_array = $this->invoiceService->getAccessKeys();
        return response()->json($access_key_array,200);
    }

    public function getInvoiceByAccessKey(Request $request) {
        $access_key = $request->input('access_key');
        $decode = $request->input('decode');
        try {
            $invoice = $this->invoiceService->getInvoiceByAccessKey($access_key, $decode);
        } catch (EntityNotFoundException $e) {
            return response()->json('Entity Not found',400);
        }
        return response()->json($invoice,200);
    }
}
