<?php
namespace App\Http\Controllers\Admin\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\InvoiceRowRequest;
use App\Models\InvoiceRow;

class InvoiceRowController extends Controller
{



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(InvoiceRowRequest $request)
    {
        $invoiceRow = new InvoiceRow($request->all());
        $invoiceRow->save();
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\InvoiceRow  $invoiceRow
     * @return \Illuminate\Http\Response
     */
    public function destroy($invoice_row_id)
    {
        $invoiceRow = InvoiceRow::whereId($invoice_row_id)->first();
        $invoiceRow->delete();
    }


   
    
}
