<?php

namespace App\Models;

use App\Commentable;
use App\Billable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Quote extends Model
{
    use HasFactory;
    use Commentable, Billable;
    
    protected $fillable = [
        'project_id',
        'address_id',
        'number',
        'status',
        'creation_date',
        'validity_date',
        'notes',
        'discount_euro',
        'discount_unit',
        'sell_total',
        'sell_total_ttc',
    ];

    
    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    public static function getAllStatus()
    {
        return [
            'draft' => 'Brouillon',
            'waiting for validation' => 'En Attente de validation',
            'valid' => 'Validé',
            'refuse' => 'Refusé'
        ];
    }

    public static function getDefaultDates($months)
    {
        return [
            "creation_date" => Carbon::today()->format('Y-m-d'),
            "validity_date" => Carbon::today()->addMonths($months)->format('Y-m-d')
        ];
    }


    public static function getUnits(){
        return [
            'piece(s)' => 'pièce(s)',
            'percentage' => '%',
            'hour(s)' => "heure(s)",
            'day(s)' => 'jour(s)',
            'week(s)' => 'semaine(s)',
            'month(s)' => 'mois(s)',
            'year(s)' => 'année(s)',
            'point(s)' => 'point(s)'
        ];
    }


    public static function getTva()
    {
        return [
            "5.00" =>  "5.00",
            "10.00" =>  "10.00",
            "20.00" => "20.00"
        ];
    }


    // Ouvrir la création d'un devis

    public function create($crud = false)
    {

        return '<a class="btn btn-primary" target="_blank" href="/admin/api/quote/new-quote" data-toggle="tooltip" ><i class="fa fa-plus"></i> Ajouter un Devis</a>';
    }


    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */


    public function project()
    {
        return $this->belongsTo(Project::class);
    }


    public function rows()
    {
        return $this->hasMany(QuoteRow::class)->orderBy('order');
    }


    public function clientAddress()
    {
        return $this->belongsTo(Address::class , 'address_id');
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




    public function getCreationDateAttribute()
    {

        return Carbon::parse($this->attributes['creation_date'])->format('Y-m-d') ;
    }

    public function getValidityDateAttribute()
    {
        return Carbon::parse($this->attributes['validity_date'])->format('Y-m-d') ;
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */



    public function setNameAttribute($value)
    {
        $this->attributes['name'] = ($value ?? (request()->hasFile('path') ? pathinfo(request()->file('path')->getClientOriginalName(), PATHINFO_FILENAME) : 'Inconnu'));
    }


    public function setNumberAttribute($value){



        // Recupération du dernier devis
        $lastQuote = Quote::all()->last();

        // Transformation du numéro en numéro suivant exemple " D21-1" , on extrait le "1" et on lui ajoute 1 ;
        if($lastQuote){
               $newIndexNumber = (int)substr($lastQuote->number , 4 , strlen($lastQuote->number) ) + 1;
        }else{
            $newIndexNumber = 1;
        }



        // récupération de l'anné de création

        $year = substr($value , 2 , strlen($value));

        $result = "D".$year."-" ;

        if(strlen($newIndexNumber) < 3){
            for ($i=0; $i < 3-strlen($newIndexNumber); $i++) {
                $result = $result."0";
            }
        }

        $result = $result.$newIndexNumber;

        if( $value && !str_contains($value , "D")){

            $this->attributes['number'] =  $result;
        }



    }


    public function setCreationDateAttribute($value)
    {
        $time = strtotime($value);

        $this->attributes['creation_date'] = date('Y-m-d',$time);
    }


    public function setValidityDateAttribute($value)
    {
        $time = strtotime($value);

        $this->attributes['validity_date'] = date('Y-m-d',$time);
    }
}
