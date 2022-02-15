<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use \Backpack\CRUD\app\Models\Traits\CrudTrait;
    use HasFactory;

    protected $table = 'projects';
    protected $fillable = ['state','client_id' ,'project_number','title' ,'description' ,'created_at'];




    public static function boot()
    {
        parent::boot();
        static::saving(function($project) {
            if(!$project->id){
                $project->project_number = $project->getProjectNumber();
            }
            
        });
    }
    
    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function client()
    {
        return $this->belongsTo(Client::class)->orderBy('name');
    }

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    public static function getAllStates()
    {
        return [
            'in progress' => 'En cours',
            'cancel'=>'Annulé',
            'waiting for payment'=>'En attente de paiement',
            'paid' => 'Payé',
            'unpaid invoice' => 'Facture impayée'
        ];
    }


    private function getProjectNumber()
    {   
        $lastProject= Project::orderBy('created_at')->first();
        if($lastProject){
            return (int)$lastProject->project_number + 1;
        }
        return 1;
    }


}
