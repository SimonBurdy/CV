<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Formation extends Model
{
    use \Backpack\CRUD\app\Models\Traits\CrudTrait;
    use HasFactory;

    protected $table = 'formations';
    protected $fillable = ['location' ,'name','from' ,'to','notes' ,'logo'];

    public function setLogoAttribute($value)
    {
        $attribute_name = "logo";
        $disk = 'public'; // or use your own disk, defined in config/filesystems.php
        $destination_path = "formations"; // path relative to the disk above

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
}
