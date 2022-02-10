<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Service extends Model
{
    
    use \Backpack\CRUD\app\Models\Traits\CrudTrait;
    use HasFactory;
    protected $table = 'services';
    protected $fillable = ['ref' , 'desc' ,'notes'];


         /*
    |--------------------------------------------------------------------------
    | FUNCTION
    |--------------------------------------------------------------------------
    */

    public static function boot()
    {
        parent::boot();
    

        static::created(function ($service) {

            //on genere la ref 
            $service->generateRef();
            $service->save();

        });

    }   


    private function generateRef()
    {
        $this->ref = 'SRV'.$this->id ;
        return true;
    }
}
