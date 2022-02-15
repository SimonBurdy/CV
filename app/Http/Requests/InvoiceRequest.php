<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Foundation\Http\FormRequest;

class InvoiceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // only allow updates if the user is logged in
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        $rules =[];
        if($this->isMethod('post')){
            $rules =  [
                'creation_date' => 'required',
                'validity_date' => 'required',
                'sell_total'=> 'required',
                'status'=>'required'
                
   
           ];
        }

        if($this->isMethod('put')){
            $rules =  [
                'project_id' => 'required',
                'creation_date' => 'required',
                'validity_date' => 'required',
                'sell_total'=> 'required',
                'status'=>'required'
   
           ];
        }
        
        if(isset(request()->rows)){       
            foreach(request()->rows as $row){
            
                if($row['article_type' ] == "App\\Models\\Product"){
                    $rules = $rules + [
                        'rows.*.article_type' => 'required', 
                        'rows.*.article_id'=> 'required', 
                        // 'rows.*.product_variant_id' => 'required', 
                        // 'rows.*.marking_id' => 'required', 
                        // 'rows.*.marking_nb_colors'=> 'required',
                        // 'rows.*.marking_nb_colors_is_max'=> 'required', 
                        'rows.*.quantity'=> 'required', 
                        'rows.*.unity'=> 'required',
                        'rows.*.vat_rate'=> 'required',
                        'rows.*.unit_price'=> 'required|numeric',
                        'rows.*.discount_euro'=> 'required',
                        'rows.*.discount_unit'=> 'required', 
                        'rows.*.description' => 'required',
                
                    ];          
                }
            }
        }

        return $rules;    
        


        
     

       
    }

    /**
     * Get the validation attributes that apply to the request.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            //
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'creation_date.required' => "Veuillez saisir une date de création valide",
            'validity_date.required' => "Veuillez saisir une date d'échance valide",
            'address_id.required' => 'Veuillez saisir une adrese de facturation',
            'number.required' => 'Veuillez vérifier le numéro de devis ',
            'sell_total.required'=> "Le total des vents n 'est pas calculé",
            'status.required'=>'Le status est invalide',
            'rows.*.article_type.required' => "Le type de l'article est obligatoire", 
            'rows.*.article_id.required'=> 'Le champ produit est obligatoire', 
            'rows.*.product_variant_id.required' => 'Le champ variante est obligatoire', 
            'rows.*.marking_id.required' => 'Le champ marquage est obligatoire', 
            'rows.*.marking_nb_colors.required'=> 'Le champ nombre de couleurs est obligatoire',
            'rows.*.marking_nb_colors_is_max.required'=> 'Le champ nombre de couleurs max est obligatoire', 
            'rows.*.quantity.required'=> 'Le champ quantité est obligatoire', 
            'rows.*.unity.required'=> 'Le champ unité est obligatoire',
            'rows.*.vat_rate.required'=> 'Le champ TVA est obligatoire',
            'rows.*.unit_price.required'=> 'Le champ prix unitaire est obligatoire',
            'rows.*.discount_euro.required'=> 'Le champ réduction euro est obligatoire',
            'rows.*.discount_unit.required'=> 'Le champ unité de réduction est obligatoire', 
            'rows.*.description.required' => 'Le champ desription est obligatoire',
        ];
    }
}
