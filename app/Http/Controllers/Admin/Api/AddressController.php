<?php
namespace App\Http\Controllers\Admin\Api;

use App\Models\Address;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Client;

class AddressController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }
    
    // Faire en sorte  qu'il y a seulement une seul adresse principale par client et par status

     private function verificationIsMainChecked($request , $address , $addressId){

    
        // SI is-main est envoyé 
        if($request->is_main){
            if($addressId){ // on passe dans l'edit d'un adresse 
                $otherAddresses = Address::where('id','!=',$addressId)->whereAddressableId($address->addressable_id)->whereIsMain(1)->get();

            }else{ // on passe dans la création d'une addresse 
                $otherAddresses = Address::whereAddressableId($address->addressable_id)->whereIsMain(1)->get();
            }


            // On passe toute les is_main des  addresses à 0 
            if($otherAddresses->count()){
                $otherAddresses->map(function($address){
                    $address->is_main = 0;
                    $address->save();
                });
            }
        
            // on mets le is_main de l'addresse crée ou édité à 1 
            $address->is_main = 1;
            
        }else{
            $otherAddresses = Address::whereAddressableId($address->addressable_id)->get();

            if(!$otherAddresses->count()){
                $address->is_main =1;
            }else if($otherAddresses->count()){
                if($otherAddresses->where('is_main' , '1')->count()){
                    $address->is_main = 0;
                }else{
                    $address->is_main = 1;
                }
            } 
                
         
        }

        return $address;
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
          //Création nouvelle Address
          $newAddress = new Address($request->all());
          $this->verificationIsMainChecked($request ,$newAddress , null );
          $newAddress->save();
          return  $newAddress;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Address  $address
     * @return \Illuminate\Http\Response
     */
    public function show(Address $address)
    {
        //
    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Address  $address
     * @return \Illuminate\Http\Response
     */
    public function edit(Address $address)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Address  $address
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,  $address_id)
    {
      
        $updateAddress = Address::findOrFail($address_id);
        $updateAddress->fill($request->all());
        $this->verificationIsMainChecked($request ,$updateAddress , $address_id);
        $updateAddress->save();
        return  $updateAddress;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Address  $address
     * @return \Illuminate\Http\Response
     */
    public function destroy($address_id)
    {
        $deleteAddress = Address::findOrFail($address_id);
        $deleteAddress->delete();

        // On indique que si on supprime l'adresse par défault on définit une autre addresse par défault
        $otherAddresses = Address::whereAddressableId( $deleteAddress->addressable_id)->get();
        if($deleteAddress->is_main && !$otherAddresses->where('is_main' ,'1')->count()){
            if($otherAddresses->count()){
                $newMain = $otherAddresses->first() ;
                $newMain->is_main = 1 ;
                $newMain->save();
            }
        }

    
        return $deleteAddress;
    }
}
