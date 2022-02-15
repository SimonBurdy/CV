<?php

namespace App\Http\Controllers\Admin\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\InvoiceRequest;
use App\Models\Invoice;
use App\Models\InvoiceRow;
use App\Models\File;
use PDF;

class InvoiceController extends Controller
{
    public function index( $project_id)
    {

        $invoices = Invoice::whereProjectId($project_id)->with('comments','rows')->get();
        
   
        return $invoices ;
       

    }


        /**
     * Permet d'afficher le devis d'un projet 
     *
     * @param [int] $invoice_id
     * @return Invoice
     */
    public function show(int $invoice_id)
    {
        $invoice = Invoice::whereId($invoice_id)->with('rows.article')->first();


        $invoice->rows->transform(function($row) {
            
            if($row->article_type == "App\\Models\\Product"){
 
                if($row->article){
                    $row->article->append('colors');
                    $row->article->append('is_price_computable');
                 
                }   
    
                
            }   
            return $row;
            
        });

        
        return $invoice;
    }


    private function computeSellTotalTcc($rows){

        $sell_total_ttc = 0;
        foreach( $rows as $row){
            $sell_total_ttc += $row['sell_total'] + ($row['sell_total'] * ($row['vat_rate']/100));
        }

        return $sell_total_ttc;
    }
        /**
     * PErmet de sauvegader
     *
     * @param InvoiceRequest $request
     * @return Invoice
     */
    public function store(InvoiceRequest $request)
    {

        
        
      
        // Récupération de la date
        $year = substr($request->creation_date , 0 ,4);
        $newInvoice = new Invoice(); // Crée un nouveau invoice
      
        $newInvoice->fill($request->all());
        $newInvoice->sell_total_ttc = $this->computeSellTotalTcc($request->rows);
        $newInvoice->save();

        // Remplie les lignes
        foreach( $request->rows as $index => $row){
            
            $row['invoice_id'] = $newInvoice->id ;
            $row['order'] = $index;
            $newRow = new InvoiceRow($row);
            $newInvoice->rows()->save($newRow);
        }

       
        return Invoice::whereId($newInvoice->id)->with('rows')->first();
    }



    /**
     * Permet d'updater un devis et les lignes
     *
     * @param Request $request
     * @param [int] $id
     * @return Invoice and InvoiceRow
     */
    public function update(InvoiceRequest $request, int $id)
    {
      
 
        // Récupération de la date
        $year = substr($request->creation_date , 0 ,4);
        // Invoice selectionné
        $updateInvoice = Invoice::whereId($id)->first();
        $updateInvoice->fill($request->all());

        if($request->status == "waiting for payment"){
            $dates = Invoice::getDefaultDates(1);
            $updateInvoice->creation_date = $dates['creation_date'];
            $updateInvoice->validity_date = $dates['validity_date'];
        }

        $updateInvoice->sell_total_ttc = $this->computeSellTotalTcc($request->rows);
        $updateInvoice->number = null;
        $updateInvoice->save(); // On enregistre le devis 


        // on updates des row  
        if(isset($request->rows)){

            foreach( $request->rows as $row){
                
                // Si il existe déja 
                if(isset($row["id"])){
                    $updateRow = InvoiceRow::findOrFail($row["id"]);
                    
                }else{ // Sinon
                    
                    $updateRow = new InvoiceRow();
                    $updateRow->invoice_id = $updateInvoice->id;
                    $updateRow->article_type = $request->article_type;
                }
                
                // On attribut l'odre
           
                $updateRow->fill($row);
                $updateInvoice->rows()->save($updateRow);
            }
        }   
        
 
        return  Invoice::whereId($id)->with('rows')->first(); 
        
    }



    public function updateFile(Request $request, $id)
    {
        $this->validate($request, [
            'path' => 'required'
        ]);

        $invoice = Invoice::findOrFail($id);
        $invoice->file->path = $request->path;
        $invoice->file->save();
        return 'ok';
    }


    public function destroy($id)
    {
       
        $invoice = Invoice::findOrFail($id);
        $invoice->rows->each(function($row) {
            $row->delete();
        });
        $invoice->delete();

        return 'ok';
    }


    public function download($invoice_id)
    {
        $invoice = Invoice::findOrFail($invoice_id) ;
        
        return \Storage::disk('s3')->download($invoice->file->path , $invoice->file->name);
        
    }





    /**
     * Afficher un pdf
     *
     * @param [int] $id
     * @return PDF stream
     */

    public function printPdf($id)
    {
        
        $invoice = Invoice::with('rows' , 'project.client' , 'clientAddress')->whereId($id)->first();
       
     
        $pdf = PDF::setOptions([
            'isHtml5ParserEnabled' => true, 
            'isRemoteEnabled' => true,
            'enable_remote' => true,
            'chroot'  => base_path(),
            'isPhpEnabled' => true
        ])->loadView('pdf/invoice', ['invoice' => $invoice]);
    
        
       if($invoice->status == "draft"){
            return $pdf->stream('Facture Proforma -'.$invoice->project->client->name . '.pdf');
       }

       return $pdf->stream( 'Facture '.$invoice->number.'-'.$invoice->project->client->name.'.pdf');
    }

    
    public function getConfig()
    {
        return [
            'options_status' =>  Invoice::getAllStatus(),
            'default_dates' => Invoice::getDefaultDates(1),
            'units' => Invoice::getUnits(),
            'tva' => Invoice::getTva(),
        ];
    }



}
