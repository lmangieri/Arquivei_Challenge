<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

/*
Route::namespace('/API')->group(function(){
    Route::post('/loadInvoicesFromArquivei','ApiInvoicesController@loadInvoicesFromArquivei');
}); */

Route::post('invoices/loadInvoicesFromArquivei', 'Api\ApiInvoicesController@loadInvoicesFromArquivei');

Route::get('invoices/getAccessKeys', 'Api\ApiInvoicesController@getAccessKeys');

Route::get('invoices/getInvoiceByAccessKey', 'Api\ApiInvoicesController@getInvoiceByAccessKey');
