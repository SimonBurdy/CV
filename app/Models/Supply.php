<?php

namespace App\Models;

use App\Commentable;
use Illuminate\Database\Eloquent\Model;
use App\Mail\BillingInserted;
use Mail;

class Supply extends Model
{

    use Commentable;



    protected $fillable = ['project_id' , 'path' ,'supplier' , 'number' , 'total_supply' , 'total_supply_net' , 'status' , 'vat_rate'];




    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

     /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    public static function getAllStatus()
    {
        return [
            'to be paid upon receipt' => 'À payer à reception',
            'to be paid when due' => 'À payer à échéance',
            'direct debit' => 'Prélèvement automatique',
            'paid in credit card' => 'Payé en CB',
            'paid by bank transfer' => 'Payé par virement',
            'paid' => 'Payé'
        ];
    }


    public static function getTva()
    {
        return [
            "0.00" =>  "0.00",
            "2.1" =>  "2.1",
            "5.5" => "5.5",
            "8.5" => "8.5",
            "10.00" =>"10.00" ,
            "13.00" =>  "13.00",
            "20.00" => "20.00" 
        ];
    }

    public function setPathAttribute($value)
    {
        $attribute_name = "path";
        $disk = 'public'; // or use your own disk, defined in config/filesystems.php
        $destination_path = "supply"; // path relative to the disk above

        // if the image was erased
        if ($value == null) {
            // delete the image from disk
            \Storage::disk($disk)->delete($this->{$attribute_name});

            // set null in the database column
            $this->attributes[$attribute_name] = null;

            return true;
        }

        // if a base64 was sent, store it in the db
        if (str_starts_with($value, 'data:image')) {
            // 0. Make the image
            $image = \Image::make($value)->encode('jpg', 90);
            // 1. Generate a filename.
            $filename = md5($value . time()) . '.jpg';
            // 2. Store the image on disk.
            \Storage::disk($disk)->put($destination_path . '/' . $filename, $image->stream(), 'public');
            // 3. Save the public path to the database
            // but first, remove "public/" from the path, since we're pointing to it from the root folder
            // that way, what gets saved in the database is the user-accesible URL

            $this->attributes[$attribute_name] = $filename;

            return true;
        }

        // cas "normal"
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
    | MUTATORS
    |--------------------------------------------------------------------------
    */

}
