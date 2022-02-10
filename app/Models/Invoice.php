<?php

namespace App\Models;

use App\Commentable;
use App\Billable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Invoice extends Model
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
        'agefiph_total',
        'notes',
        'discount_euro',
        'discount_unit',
        'sell_total',
        'sell_total_ttc',
    ];


      // masque de facturation ex : 2022xxx
      protected $mask;

      protected $append = ['is_allowed'];
  
  
      public function __construct(array $attributes = [])
      {
          parent::__construct($attributes);
  
          $this->mask = Config::get('settings.billing_year',date('Y'));
      }
  
  
      public static function boot()
      {
          parent::boot();
  
          static::saving(function ($invoice) {
              if($invoice->status != 'draft'){
                  if(!$invoice->number){
                      $invoice->number = $invoice->getNumber($invoice->mask);
  
                  }
              }
  
              // todo je comprend pas trop ça ? ça risque d envoyer 1  mail a chaque fois qu'on save
              // dans le doute je commente
  //            $revision = $invoice->revisionHistory()->where('key' , 'status')->where('old_value'  ,'unpaid invoice' )->where('new_value' , 'paid')->first();
  //
  //            if($revision){
  //
  //                $client = $invoice->project->client;
  //                $project = $invoice->project;
  //                $file = null;
  //                $userResponsible = $revision->userResponsible()->email;
  //
  //
  //                $data = [
  //                    "subject" => $client->name." - Projet ".$project->project_number." - Facture n° ".$invoice->number,
  //                    "body" => $client->name." - Projet ".$project->project_number." - Facture n° ".$invoice->number,
  //                    "file"=> $file
  //                ];
  //
  //                Mail::to($userResponsible)->send(new BillingInserted($data["subject"] , $data["body"] , $data['file']));
  //
  //            }
  
  
          });
  
  
      }
  
      /*
      |--------------------------------------------------------------------------
      | FUNCTIONS
      |--------------------------------------------------------------------------
      */
  
  
  
  
  
      public static function getAllStatus()
      {
          return [
              'draft' => 'Brouillon',
              'waiting for payment' => 'En Attente de paiement',
              'unpaid invoice' => 'Facture impayée',
              'partially paid' => 'Partiellement payé',
              'paid' => 'Payé'
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
  
  
      /**
       * Permet de determiner le numéro de la facture
       */
      public function getNumber($value)
      {
  
          $lastInvoice = Invoice::isValidated()->currentExercise()->orderByDesc('number')->first();
  
  
          if($lastInvoice){
                 $newIndexNumber = (int)substr($lastInvoice->number , 4 , strlen($lastInvoice->number) ) + 1;
          }else{
              $newIndexNumber = 1;
          }
  
          // récupération de l'année de création
  
  
          $result = $value ;
  
          if(strlen($newIndexNumber) < 3){
              for ($i=0; $i < 3-strlen($newIndexNumber); $i++) {
                  $result = $result."0";
              }
  
          }
  
          return  $result.$newIndexNumber;
  
  
  
  
  
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
  
  
  
      public function file()
      {
          return $this->morphOne(File::class, 'fileable');
      }
  
  
  
      public function rows()
      {
          return $this->hasMany(InvoiceRow::class)->orderBy('order');
      }
  
  
      public function clientAddress()
      {
          return $this->belongsTo(Address::class , 'address_id');
      }
  
       /*
      |--------------------------------------------------------------------------
      | ACCESSOR
      |--------------------------------------------------------------------------
      */
  
      public function getDownloadableFileLink()
      {
          if (isset($this->file_path)) {
              return '<a href="'.url($this->file_path).'" target="_blank">Download</a>';
          }
  
          return ' Pas de fichier';
      }
  
  
  
       /***
       *
       */
  
      public function getIsAllowedAttribute()
      {
          $revison = $this->revisionHistory()->orderby('created_at', 'desc')->where('key' , 'status')->where('new_value' , '!=' , 'draft')->first();
  
  
          if($revison && \Auth::user()){
              $lastToValidate =   $revison->userResponsible();
              return $revison->userResponsible()->id !== \Auth::user()->id;
  
          }
  
          return false;
      }
  
  
  
  
  
  
  
      /*
      |--------------------------------------------------------------------------
      | MUTATORS
      |--------------------------------------------------------------------------
      */
  
      public function setFilePathAttribute($value)
      {
          $attribute_name = "file_path";
          $disk = "s3";
          $destination_path = "invoices";
  
  
            // if the logo was erased
            if ($value == null) {
              // delete the logo from disk
              \Storage::disk($disk)->delete($this->{$attribute_name});
  
              // set null in the database column
              $this->attributes[$attribute_name] = null;
          }
  
  
          $this->uploadFileToDisk($value, $attribute_name, $disk, $destination_path);
  
  
      }
  
  
      public function setNumberAttribute($value){
          // Recupération du dernier invoice
  
  
          if($value && str_contains($value , $this->mask)){
              $this->attributes['number'] = null;
          }
  
  
         if( $value && !str_contains($value , "D")){
  
              $this->attributes['number'] =  $value;
         }
      }
  
  
      public function setAgefiphTotalAttribute()
      {
          $this->attributes['agefiph_total'] = $this->computeAgefiph();
      }
  
  
  
  
      /*
      |--------------------------------------------------------------------------
      | SCOPES
      |--------------------------------------------------------------------------
      */
  
      public function scopeIsValidated($query)
      {
          return $query->where('status' , '!=' , 'draft');
      }
    
      public function scopeCurrentExercise($query)
      {
          return $query->where('number' , 'LIKE' , $this->mask.'%');
      }
  
  
}
