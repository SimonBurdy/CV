<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    protected $table = 'addresses';
    protected $fillable = ['addressable_type','addressable_id','is_main','name','address','phone'];


        /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function addressable()
    {
        return $this->morphTo();
    }


    public function client()
    {
        return $this->where('addressable_type' , "App\Models\Client")->belongsTo(Client::class);
    }

}
