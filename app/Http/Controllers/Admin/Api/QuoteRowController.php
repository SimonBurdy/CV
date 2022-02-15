<?php
namespace App\Http\Controllers\Admin\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\QuoteRowRequest;
use App\Models\QuoteRow;

class QuoteRowController extends Controller
{



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(QuoteRowRequest $request)
    {
        $quoteRow = new QuoteRow($request->all());
        $quoteRow->save();
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\QuoteRow  $quoteRow
     * @return \Illuminate\Http\Response
     */
    public function destroy($quote_row_id)
    {
        $quoteRow = QuoteRow::whereId($quote_row_id)->first();
        $quoteRow->delete();
    }


   
    
}
