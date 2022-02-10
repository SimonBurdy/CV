<?php

use Illuminate\Support\Facades\Route;

// --------------------------
// Custom Backpack Routes
// --------------------------
// This route file is loaded automatically by Backpack\Base.
// Routes you generate using Backpack\Generators will be placed here.

Route::group([
    'prefix'     => config('backpack.base.route_prefix', 'admin'),
    'middleware' => array_merge(
        (array) config('backpack.base.web_middleware', 'web'),
        (array) config('backpack.base.middleware_key', 'admin')
    ),
    'namespace'  => 'App\Http\Controllers\Admin',
], function () { // custom admin routes
    Route::crud('project', 'ProjectCrudController');
    Route::crud('client', 'ClientCrudController');
    Route::get('fetch/client','ClientCrudController@fetchClient');
    Route::crud('experience', 'ExperienceCrudController');
    Route::crud('formation', 'FormationCrudController');

    Route::prefix('api')->group(function () {
        Route::get('/experiences' , 'Api\ExperienceController@index');
        Route::get('/formations' , 'Api\FormationController@index');
        Route::get('/projects' , 'Api\ProjectController@index');
        Route::get('/clients' , 'Api\ClientController@index');

       
        Route::post('/addresses','Api\AddressController@store');
        Route::put('/addresses/{address_id}','Api\AddressController@update');
        Route::delete('/addresses/{address_id}','Api\AddressController@destroy');


        Route::get('comments', 'Api\CommentController@index');
        Route::post('comments', 'Api\CommentController@store');
        Route::put('comments/{id}', 'Api\CommentController@update');
        Route::delete('comments/{id}', 'Api\CommentController@destroy');


        ////A VOIR

        // QUOTE
        Route::get('quotes/{quote_id}/download', 'Api\QuoteController@download')->name('quote.download.download');
        Route::get('quotes/config', 'Api\QuoteController@getConfig');
        Route::get('quotes/{project_id}', 'Api\QuoteController@index');
        Route::post('quotes', 'Api\QuoteController@store');
        Route::put('quotes/{id}', 'Api\QuoteController@update');

        Route::delete('quotes/{id}', 'Api\QuoteController@destroy');
        Route::post('quotes/{id}/updateFile', 'Api\QuoteController@updateFile');
        Route::get('quotes/{id}/convert-to-invoice' , 'Api\QuoteController@convertToInvoice');

        Route::get("quotes/editQuote/{quote_id}" , 'Api\QuoteController@show');
        Route::get('quotes/{id}/printPdf' , 'Api\QuoteController@printPdf');
        Route::get('quotes/{project_id}/generate' , 'Api\QuoteController@generate');
        Route::delete('quotes/quote-row/{quote_row_id}/delete' , 'Api\QuoteRowController@destroy');


        Route::get('quote-row/generate-product-description' , 'Api\QuoteController@generateProductDescription');
        Route::get('quote-row/generate-printing-technique-description' , 'Api\QuoteController@generatePrintingTechniqueDescription');
        Route::get('quote-row/generate-address-description' , 'Api\QuoteController@generateAddressDescription');

        // INVOICE
        Route::get('invoices/{invoice_id}/download', 'Api\InvoiceController@download')->name('invoice.download.download');
        Route::get('invoices/config', 'Api\InvoiceController@getConfig');
        Route::get('invoices/{project_id}', 'Api\InvoiceController@index');
        Route::post('invoices', 'Api\InvoiceController@store');
        Route::put('invoices/{id}', 'Api\InvoiceController@update');

        Route::delete('invoices/{id}', 'Api\InvoiceController@destroy');
        Route::post('invoices/{id}/updateFile', 'Api\InvoiceController@updateFile');
        
        Route::get("invoices/editInvoice/{invoice_id}" , 'Api\InvoiceController@show');
        Route::get('invoices/{id}/printPdf' , 'Api\InvoiceController@printPdf')->name('invoice.printPdf');
        Route::delete('invoices/invoice-row/{invoice_row_id}/delete' , 'Api\InvoiceRowController@destroy');
    });
    
    Route::crud('service', 'ServiceCrudController');
}); // this should be the absolute last line of this file