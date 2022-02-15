<?php

namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class ServiceResource extends JsonResource
{

    //protected $withRelated;


    /**
     * Create a new product resource with or without related
     * products
     *
     * @param bool $withRelated
     * @return void
     */
    // public function __construct($resource,$withRelated=false)
    // {
    //     parent::__construct($resource);
    //     $this->withRelated = $withRelated;
    // }



    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {  
        return [
            'id' => $this->id,
            'ref' => $this->ref,
            'desc' => $this->desc,
            'notes' => $this->notes

        ];

    }
}
