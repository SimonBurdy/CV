<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceRow extends Model
{
    use HasFactory;
    protected $fillable = [
        'invoice_id', 
        'article_type',
        'article_id', 
        'product_variant_id', 
        'marking_id' , 
        'marking_nb_colors', 
        'marking_nb_colors_is_max', 
        'quantity', 
        'unity',
        'vat_rate', 
        'unit_price', 
        'discount_euro',
        'discount_unit', 
        'description' ,
        'order',
        'agefiph',
        'marking_template_height',
        'marking_template_with',
        'printing_details',
        'sell_total',
 
    ];
    


    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */


    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function invoice()
    {
        return $this->belongsTo(invoice::class);
    }


    public function article()
    {   
        return  $this->morphTo();
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */


    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */


       /*
    |--------------------------------------------------------------------------
    | MUTATOS
    |--------------------------------------------------------------------------
    */



}
