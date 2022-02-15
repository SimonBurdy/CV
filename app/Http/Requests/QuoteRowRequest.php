<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class QuoteRowRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // only allow updates if the user is logged in
        return backpack_auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
        'article_type' => 'required', 
        'article_id'=> 'required', 
        'product_variant_id' => 'required', 
        'marking_id' => 'required', 
        'marking_nb_colors'=> 'required',
        'marking_nb_colors_is_max'=> 'required', 
        'quantity'=> 'required', 
        'unity'=> 'required',
        'vat_rate'=> 'required',
        'unit_price'=> 'required|numeric',
        'discount_euro'=> 'required',
        'discount_unit'=> 'required', 
        'description' => 'required',

        ];
    }
}
