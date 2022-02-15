<?php

namespace App\Http\Controllers\Admin\Api;
use App\Models\Service;
use Illuminate\Http\Request;
use App\Http\Resources\ServiceResource;
use App\Http\Controllers\Controller;

use App\Http\Requests\ServiceRequest;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $query = $request->get('q');

        if (!$query) {
            return [];
        }

        $services = Service::where('desc','like', "%".$query."%")
            ->orWhere('ref','like', "%".strtoupper($query)."%")->get();

    
        return ServiceResource::collection($services);
        

    }


    /**
     * Permet d'afficher le service 
     *
     * @param [int] $service_id
     * @return Service
     */
    public function show($service_id){

        return Service::whereId($service_id)->first();
    }


    /**
     * Ajouter  un nouveau service à la base 
     *
     * @param ServiceRequest $request
     * @return Service
     */
    public function store(ServiceRequest $request)
    {

        $newService = new Service($request->all());
        $newService->save();
        return $newService;
    }


    public function update(ServiceRequest $request , $id){
        $oldService = Service::findOrFail($id);
        $oldService->fill($request->all());
    }


    public function destroy($id)
    {
        $oldService = Service::findOrFail($id);
        $oldService->delete();
    }
    
}
