<?php

namespace App\Http\Controllers\Admin\Api;

use App\Models\Quote;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class QuoteController extends Controller
{
   /**
     * Permet d'afficher tout les devis d'un projet
     *
     * @param [int] $project_id
     * @return Quote
     */
    public function index($project_id)
    {
      
        return Quote::whereProjectId($project_id)->with('comments','rows')->get();
    }


    /**
     * Permet d'afficher le devis d'un projet 
     *
     * @param [int] $quote_id
     * @return Quote
     */
    public function show($quote_id)
    {
        $quote = Quote::whereId($quote_id)->with('rows.article')->first();


        $quote->rows->transform(function($row) {
            
            if($row->article_type == "App\\Models\\Product"){
 
                if($row->article){
                    $row->article->append('colors');
                    $row->article->append('is_price_computable');
                }   
    
                
            }   
            return $row;
            
        });


        return $quote;
    }

    /**
     * Permet de calculer  le prix total  tcc
     */
    private function computeSellTotalTcc($rows){

        $sell_total_ttc = 0;
        foreach( $rows as $row){
            $row = (object) $row;
            $sell_total_ttc += $row->sell_total + ($row->sell_total * ($row->vat_rate/100));
        }

        return $sell_total_ttc;
    }


    /**
     * PErmet de sauvegader
     *
     * @param QuoteRequest $request
     * @return Quote
     */
    public function store(QuoteRequest $request)
    {

        // Récupération de la date
        $year = substr($request->creation_date , 0 ,4);
        $newQuote = new Quote(); // Crée un nouveau quote

        $this->authorize('create' , $newQuote); // Vérifie les policies
        $newQuote->fill($request->all());
        $newQuote->sell_total_ttc = $this->computeSellTotalTcc($request->rows);
        $newQuote->number = $year;
        $newQuote->save();

        // Remplie les lignes
        foreach( $request->rows as $index => $row){

            $row['quote_id'] = $newQuote->id ;
            $row['order'] = $index;
            $newRow = new QuoteRow($row);
            $newQuote->rows()->save($newRow);
        }


        return Quote::whereId($newQuote->id)->with('rows')->first();

        //return ["res" => $newQuote ,  "role" =>  "quote"];
    }

    /**
     * Permet d'updater un devis et les lignes
     *
     * @param Request $request
     * @param [int] $id
     * @return Quote and QuoteRow
     */
    public function update(QuoteRequest $request, int $id)
    {


        // Récupération de la date
        $year = substr($request->creation_date , 0 ,4);
        // Quote selectionné
        $updateQuote = Quote::findOrFail($id);
        $this->authorize('update' ,  $updateQuote );
        $updateQuote->fill($request->all());
        $updateQuote->save(); // On enregistre le devis


        // on updates des row
        if(isset($request->rows)){

            foreach( $request->rows as $row){

                // Si il existe déja
                if(array_key_exists('id',$row)){

                    $updateRow = QuoteRow::findOrFail($row["id"]);

                }else{ // Sinon

                    $updateRow = new QuoteRow();
                    $updateRow->quote_id = $updateQuote->id;
                    $updateRow->article_type = $request->article_type;
                }

                // On attribut l'odre

                $updateRow->fill($row);
                $updateQuote->rows()->save($updateRow);
            }
        }

        return  Quote::whereId($id )->with('rows.article')->first();

    }


    /**
     * Creer la description
     */
    private function buildProductDescription($params)
    {

        return $params['desc'].' '.$params['weezea_ref'];

    }

     /**
     * Ajouter des infos  produit à la description
     */
    public function generateProductDescription(Request $request)
    {

        return $this->buildProductDescription($request->all());



    }

    private function buildPrintingTechniqueDescription($params)
    {
        $marking_technique = $params['marking_technique'] ;
        $marking_nb_colors = $params['marking_nb_colors'];
        $couleur_max = $params['couleur_max'];

        $marking_template_height = $params['marking_template_height'];
        $marking_template_width = $params['marking_template_width'];

        $printing_details = "";
        if(isset( $params['printing_details'])){
            $printing_details = $params['printing_details'];
        }


        $description = "";

        if($marking_technique ){
            $description = "\n".$description.'Marquage inclus : '.$marking_technique.' '.($marking_nb_colors  ?$marking_nb_colors  : '' ).' '.(($couleur_max != 'null' &&  $couleur_max != 0) ? $couleur_max : '' )."\n";
        }

        if(!$marking_technique && $marking_template_height && $marking_template_width){
            $description = "\n".$description."Gabarit d'impression : ".$marking_template_width."x".$marking_template_height."mm \n "  ;
        }else if($marking_technique && $marking_template_height && $marking_template_width){
            $description = $description."Gabarit d'impression : ".$marking_template_width."x".$marking_template_height."mm \n "  ;
        }


        if($printing_details){
            $description = $description.$printing_details."\n"  ;
        }


        return $description;
    }
    /**
     * Ajouter des infos  produit à la description
     */
    public function generatePrintingTechniqueDescription(Request $request)
    {


          return $this->buildPrintingTechniqueDescription($request->all());

    }



    private function buildAddressDescription($params)
    {


        if(isset($params['name']) && isset($params['addressName'])){
            $name = $params['name'] ;
            $addressName = $params['addressName'];
            $status = $params['status'];
            $addressName = str_replace("\r" ,'', $addressName);


            if($name && $addressName){

                if($status == "billing"){
                    return "Adresse de Facturation : \n ".$name." \n ".$addressName." \n";
                }

                if($status == "delivery"){
                    return "Adresse de Livraison : \n ".$name." \n ".$addressName." \n";
                }


            }
        }

        return "Adresse à préciser" ;

    }

    /**
     * protected function generate Addresse à la description
     */

    public function generateAddressDescription(Request $request)
    {
        return $this->buildAddressDescription($request->all());

    }


    /**
     * Recupération sell total de chaque details - les shipping_fees:
     */

    private function computeDetailsSellTotal($project)
    {
        return $project->propal->each(function($propalProduct){
            return $propalProduct->details->map(function($detail){
                return  $detail->sell_total   -= $detail->shipping_fees ;
            });
        });
    }

    /**
     * Recupération de la somme d'un attribut de chaque detail
     */


    private function computeSumFilteredDetails($filteredDetails , $attr)
    {
        return $filteredDetails->map(function($propalProduct) {
            return  $propalProduct->details->filter(function($detail){
                return $detail->quote == 1 ;
            });
        })->flatten()->sum($attr);
    }


    /**
     * Permet de selectionner la bonne addresse  en fonction des paramètres
     */


    private function findBestAddress($project , $newQuote , $delivery = true , $billing = true , $main = true)
    {
        $allClientAddresses = $project->client->addresses() ;

        // on cherche la meilleur delivery address
        if($delivery){
            $deliveryAddresses = $allClientAddresses->where('statuses' , 'LIKE' , '%delivery address%');

            if($deliveryAddresses->count()){
                if($main){
                    $mainDeliveryAddress = $deliveryAddresses->where('is_main' , true)->first();
                        return $mainDeliveryAddress ? $mainDeliveryAddress : null ;
                }

                return $deliveryAddresses->first();

            }
        }


        // on cherche la meilleur billing address
        if($billing){
            $billingAddresses = $allClientAddresses->where('statuses' , 'LIKE' , '%billing address%');

            if($billingAddresses->count()){
                if($main){
                    $mainBillingAddress = $billingAddresses->where('is_main' , true)->first();
                        return $mainBillingAddress ? $mainBillingAddress : null ;
                }

                return $billingAddresses->first();

            }
        }

        return ;
    }





    /**
     * Creation d'une quote row pour les frais de port
     */

    private function createQuoteRowShippingFees($shippingFees ,$project , $newQuote)
    {

            // Creation du  quoteRow d'un service de frais de transport  si il existe
            if($shippingFees > 0){
                $serviceShippingFees = Service::shippingFees()->first();


                $bestAddress =  $this->findBestAddress($project , $newQuote , $delivery = true , $billing = false , $main = true);

                $newServiceQuoteRow = new QuoteRow();
                $newServiceQuoteRow->quote_id = $newQuote->id;
                $newServiceQuoteRow->article_type  = "App\Models\Service";
                $newServiceQuoteRow->article_id = $serviceShippingFees->id ;
                $newServiceQuoteRow->quantity =  1;
                $newServiceQuoteRow->discount_euro = 0 ;
                $newServiceQuoteRow->discount_unit = "euros";
                $newServiceQuoteRow->unity =  "point(s)" ;
                $newServiceQuoteRow->vat_rate = "20.00" ;
                $newServiceQuoteRow->unit_price = $shippingFees ;
                $newServiceQuoteRow->sell_total =  $newServiceQuoteRow->quantity * $newServiceQuoteRow->unit_price;
                $newServiceQuoteRow->order =  $newQuote->rows->count() ;


                $newServiceQuoteRow->description = $serviceShippingFees->desc."\n".$this->buildAddressDescription(["name" => $bestAddress ? $bestAddress->name  : null,"addressName" => $bestAddress ? $bestAddress->address : null,"status" =>  "delivery"]);

                $newServiceQuoteRow->save();
            }


    }

    /**
     * Génére le devis et ses lignes à partir de la propal
     *
     * @param [int] $project_id
     * @return Quote and QuoteRox
     */
    public function generate($project_id)
    {

        $project = Project::whereId($project_id)->with('propal.details.product' , 'propal.details.printingInfos' , 'client')->first();


        if($project->propal){


            // Recupération sell total de chaque details - les shipping_fees:
            $detailSellTotalWithoutShippingFees = $this->computeDetailsSellTotal($project);


            // Recupération du sell Total des produits coché devis
            $quoteSellTotal =  $this->computeSumFilteredDetails($detailSellTotalWithoutShippingFees , "sell_total");


            // Recupération total  agefiph
            $quoteAgefiphTotal = $this->computeSumFilteredDetails($detailSellTotalWithoutShippingFees , "agefiph");


            // Fais de port
            $shippingFees = 0 ;
            $shippingFees  = $project->propal->first()->details->first()->shipping_fees;


            // Creation du Quote
            $newQuote = new Quote();
            $this->authorize('create' , $newQuote); // vérification des policies
            $newQuote->project_id = $project->id ;

            // on determine la meilleur addresse pour le devis
            $bestAddress =  $this->findBestAddress($project , $newQuote , $delivery = false , $billing = true , $main = true);
            $newQuote->address_id = $bestAddress ? $bestAddress->id : null;


            // Gestion du devis
            $newQuote->creation_date = Quote::getDefaultDates(2)['creation_date'];
            $newQuote->validity_date = Quote::getDefaultDates(2)['validity_date'];
            $newQuote->number = date( 'Y',strtotime(Quote::getDefaultDates(2)['creation_date'])) ;
            $newQuote->sell_total = $quoteSellTotal + $shippingFees;
            $newQuote->discount_euro = 0;
            $newQuote->status = 'draft' ;  // par défaut on mets en brouillon
            $newQuote->agefiph_total = $quoteAgefiphTotal;
            $newQuote->save();




            // Creation de toutes les QuoteRows produit


            $quoteOrder = 0 ;
            $project->propal->each(function($product ) use ($newQuote , &$quoteOrder){

                 $product->details->each(function($detail )  use ( $product , $newQuote ,&$quoteOrder ) {

                    if($detail->quote){
                        $newProductQuoteRow = new QuoteRow() ;
                        $newProductQuoteRow->quote_id =  $newQuote->id ;
                        $newProductQuoteRow->article_type =  "App\Models\Product";
                        $newProductQuoteRow->article_id=  $detail->product->product_id;
                        $newProductQuoteRow->product_variant_id = $product->color_id;
                        $newProductQuoteRow->unity =  'piece(s)';
                        $newProductQuoteRow->vat_rate = '20.00';
                        $newProductQuoteRow->unit_price = $detail->sell_unit_price;
                        $newProductQuoteRow->discount_euro = $detail->discount_euro ? $detail->discount_euro : 0 ;
                        $newProductQuoteRow->discount_unit = $detail->discount_unit ;




                        $newProductQuoteRow->agefiph = $detail->agefiph ;
                        $newProductQuoteRow->sell_total = $detail->sell_total ;
                        $newProductQuoteRow->fill($detail->toArray());


                        $newProductQuoteRow->order = $quoteOrder ;
                        $quoteOrder++;

                        // On trouve la bonne couleur
                        $variantColor = Color::whereId($detail->product->color_id)->first();


                        $newProductQuoteRow->description = $this->buildProductDescription( ["desc" => $detail->projectProduct->product->desc , "weezea_ref" => $detail->projectProduct->product->weezea_ref , "variant_color" => $variantColor ? $variantColor->name : ""]);
                        $detail->printingInfos->each(function($printingInfo) use($newProductQuoteRow){
                            $newProductQuoteRow->description = $newProductQuoteRow->description.$this->buildPrintingTechniqueDescription([
                                "marking_technique" => $printingInfo->displayable_marking_name,
                                "marking_nb_colors" => $printingInfo->marking_nb_colors ,
                                "couleur_max" => $printingInfo->marking_nb_colors_is_max,
                                "marking_template_height" => (int)$printingInfo->marking_template_width ,
                                "marking_template_width" => (int)$printingInfo->marking_template_height,
                                "printing_details" => $printingInfo->printing_details ]
                            );
                        });




                        $newProductQuoteRow->save();
                    }


                });
            });




            // on ajoute une QuoteRow service si un frais de port existe
            $this->createQuoteRowShippingFees($shippingFees ,$project , $newQuote);


            return Quote::whereId($newQuote->id)->with('rows.article')->first();

        }
    }



    /**
     * Supprimer devis
     *
     * @param int  $id
     * @return void
     */
    public function destroy($id)
    {

        $quote = Quote::findOrFail($id);
        $this->authorize('delete' , $quote);
        $quote->rows->each(function($row) {
            $row->delete();
        });
        $quote->delete();

        return 'ok';
    }



    /**
     * Afficher un pdf
     *
     * @param [int] $id
     * @return PDF stream
     */

    public function printPdf($id)
    {

        $quote = Quote::with('rows' , 'project.client' , 'clientAddress')->findOrFail($id);

        $this->authorize('printPdf' , $quote);


       $pdf = PDF::setOptions([
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true,
            'enable_remote' => true,
            'chroot'  => base_path(),
            'isPhpEnabled' => true
        ])->loadView('pdf/quote', ['quote' => $quote]);


        return $pdf->stream('Devis '.$quote->number.'-'.$quote->project->client->name.'.pdf');
    }


    public function getConfig()
    {
        return [
            'options_status' =>  Quote::getAllStatus(),
            'default_dates' => Quote::getDefaultDates(2),
            'units' => Quote::getUnits(),
            'tva' => Quote::getTva(),
        ];
    }


    public function convertToInvoice($id)
    {

        $quote = Quote::with('rows' , 'project.client' , 'clientAddress')->findOrFail($id);
        $newInvoice = new Invoice($quote->toArray());

        $dates = Invoice::getDefaultDates(1);
        $newInvoice->creation_date = $dates['creation_date'];
        $newInvoice->validity_date = $dates['validity_date'];
        $newInvoice->status = "draft";
        $newInvoice->number = null ;
        $newInvoice->sell_total_ttc = $this->computeSellTotalTcc($quote->rows);
        $newInvoice->save();

        $quote->rows->each(function($row) use ( $newInvoice){
            $newInvoiceRow = new InvoiceRow($row->toArray());
            $newInvoiceRow->invoice_id = $newInvoice->id;
            $newInvoiceRow->save();
        });



        return 'ok';

    }
}
