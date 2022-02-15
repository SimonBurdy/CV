<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ClientRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use App\Models\Client;

/**
 * Class ClientCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class ClientCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Client::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/client');
        CRUD::setEntityNameStrings('client', 'clients');

        //on load le projet car necessaire dans les crud
        $this->client = ($clientId = \Route::current()->parameter('id')) ? Client::findOrFail($clientId) : null;
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {   

        $this->crud->addColumns([
            [
                'name' => 'logo', // The db column name
                'label' => "Logo", // Table column heading
                'type' => 'image',
                'prefix' => 'storage/clients/',
                'height' => '50px',
                'width' => '50px',
            ],
            [
                'name' => 'name',
                'label' => 'Nom'
            ],
        ]);
     

        /**
         * Columns can be defined using the fluent syntax or array syntax:
         * - CRUD::column('price')->type('number');
         * - CRUD::addColumn(['name' => 'price', 'type' => 'number']); 
         */
    }

    /**
     * Define what happens when the Create operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(ClientRequest::class);
        $this->crud->addFields([
            [
                'name' => 'logo', // The db column name
                'label' => "Logo", // Table column heading
                'type' => 'image',
                'prefix' => 'storage/clients/',
                'crop' => true, // set to true to allow cropping, false to disable
                'tab' => 'Général'
            ],
            [
                'name' => 'name',
                'type' => 'text',
                'label' => 'Nom',
                'tab' => 'Général',
                'wrapper' => ['class' => 'form-group col-md-7'],

            ],
            [   //
                'name'          => 'siret',
                'label'         => 'Siret',
                'type' => 'number',
                'tab' => 'Général',
                'wrapper' => ['class' => 'form-group col-md-4'],
                'attributes' => ['autocomplete'=>'no','autofill'=>'no'],
            ],
            [
                'name' => 'notes',
                'label' => 'Notes',
                'type' => 'summernote',
                'tab' => 'Général'
            ],
           
        ]);

       
     

        /**
         * Fields can be defined using the fluent syntax or array syntax:
         * - CRUD::field('price')->type('number');
         * - CRUD::addField(['name' => 'price', 'type' => 'number'])); 
         */
    }

    /**
     * Define what happens when the Update operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();

        $this->crud->addField([
            'name'  => 'client_addresses',
            'label' => 'Adresse(s)',
            'type'  => 'address',
            'tab' => 'Adresse(s)',
            //Attribute
            'title' => 'Mon_titre',
            'client' => $this->client->append('clientAddresses')->toArray(),
           
          

        ]);

    }
}
