<?php

namespace App\Http\Controllers\Admin\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\SupplyRequest;
use App\Models\Supply;






class SupplyController extends Controller
{

    /**
     * Permet d'afficher tout les devis d'un projet
     *
     * @param [int] $project_id
     * @return Supply
     */
    public function index(Request $request)
    {   
      
        return Supply::whereProjectId($request->project_id)->with('supplier' ,'comments','files' )->get();
    }


    /**
     * Permet d'afficher le devis d'un projet 
     *
     * @param [int] $quote_id
     * @return Supply
     */
    public function show(int $quote_id)
    {
       return "ok";
    }


    /**
     * PErmet de sauvegader
     *
     * @param SupplyRequest $request
     * @return Supply
     */
    public function store(SupplyRequest $request)
    {
    
      
        $newSupply = new Supply($request->all());
        $newSupply->save();

    
       return Supply::whereId($newSupply->id)->first();
    }

    public function uploadFile(Request $request)
    {
     
        if($request->hasFile('path')){
            $supply = Supply::whereId($request->supply_id)->first();
            $supply->path =  $request->path;
            $supply->save();
           
        }


       return $supply;
    }

    public function updateFile(Request $request , int $id)
    {
       
        $oldFile = File::whereFileableId($request->supply_id)->whereCategory($request->category)->first();
        $newFile = $request->file('path');
        $oldFile->name = $newFile->getClientOriginalName();
        $oldFile->path = $request->path;
        $oldFile->save();
        return $oldFile;
    }

    /**
     * Permet d'updater un devis et les lignes
     *
     * @param Request $request
     * @param [int] $id
     * @return Supply and QuoteRow
     */
    public function update(SupplyRequest $request, int $id)
    {
        $oldSupply = Supply::findOrFail($id);
        $oldSupply->fill($request->all());
        $oldSupply->save();
       return Supply::whereId($oldSupply->id)->with('supplier' ,'files')->first();
    }


    /**
     * Supprimer devis
     *
     * @param int  $id
     * @return void
     */
    public function destroy($id)
    {
       
        $supply = Supply::findOrFail($id);
        $supply->files->each(function($file){
            $file->delete();
        });

        $supply->delete();

        return 'ok';
    }





}


