<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Foundation\Http\FormRequest;

class SupplyRequest extends FormRequest
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
            'project_id'  => 'required|numeric',
            'supplier'  => 'required',
            'total_supply'  => 'required|numeric',
            'total_supply_net'  => 'required|numeric',
            'status' => 'required'
        ];
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
            'project_id.required'  => 'Veuillez saisir un projet',
            'supplier.required'  => 'Veuillez saisir un fournisseur',
            'total_supply.required'  => 'Veuillez saisir un total HT ',
            'total_supply_net.required'  => 'Veuillez saisir un total TTC',
            'status.required' => 'Veuillez saisir un status'
        ];
    }
}
