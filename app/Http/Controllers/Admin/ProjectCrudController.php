<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ProjectRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use App\Models\Project;
/**
 * Class ProjectCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class ProjectCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
    use \App\FetchOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Project::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/project');
        CRUD::setEntityNameStrings('projet', 'projets');

          //on load le projet car necessaire dans les crud
          $this->project = ($projectId = \Route::current()->parameter('id')) ? Project::findOrFail($projectId) : null;
    }

    public function fetchClient()
    {

        return $this->fetch(\App\Models\Client::class);
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
                'name' => 'project_number',
                'label' => 'Numéro',
                'type'  => 'number',
                 'prefix'        => 'N °',
             
            ],
            [
                'name' => 'state',
                'label' => 'Etat',
                'type' => 'select_from_array',
                'options' => Project::getAllStates(),
                'wrapper' => [
                    'element' => 'div',
                    'class' => function ($crud, $column, $entry, $related_key) {
                        if ($entry['state'] == 'paid') {
                            return 'badge badge-success';
                        }
                        if ($entry['state'] == 'in progress') {
                            return 'badge badge-info';
                        }
                        if ($entry['state'] == 'waiting for payment') {
                            return 'badge badge-warning';
                        }
                        if ($entry['state'] == 'cancel') {
                            return 'badge badge-dark';
                        }
                        if ($entry['state'] == 'unpaid invoice') {
                            return 'badge badge-warning';
                        }
                        return 'badge badge-default';
                    },
                ],
                'searchLogic' => false
            ],
            [
                'name' => 'client.name',
                'type' => 'text',
                'label' => 'Client',
              
            ],
            [
                'name' => 'title',
                'label' => 'Titre'
            ],
            [
                'name' => 'created_at',
                'label' => 'Date de création',
                'type' => 'date',
                'visibleInTable' => false,
                'visibleInModal' => false,
                'visibleInExport' => true,
                'visibleInShow' => true, // sure, why not,
                'searchLogic' => false

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
        CRUD::setValidation(ProjectRequest::class);
        $this->crud->addFields([
                [
                    'name' => 'title',
                    'label' => 'Titre',
                    'type' => 'text',
                    'tab' => 'Général',
                    'wrapper' => ['class' => 'form-group col-md-6'],
                    'attributes' => [
                        'required' => true,
                    ],
                ],
                [
                    'label' => "Client",
                    'type' => "relationship",
                    'name' => 'client_id',
                    'ajax' => true,
                    'tab' => 'Général',
                    'wrapper' => ['class' => 'form-group col-md-6'],
                    'hint' => 'Recherche une entreprise ou un groupe',
                    'attributes' => [
                        'required' => true,
                        ]
                ],

                [   // select_from_array
                    'name' => 'state',
                    'label' => "Etat",
                    'type' => 'select_from_array',
                    'options' => Project::getAllStates(),
                    'allows_null' => false,
                    'default' => 'open',
                    'wrapper' => ['class' => 'form-group col-md-6'],
                    'tab' => 'Général'
                ],
                [
                    'name' => 'description',
                    'label' => 'Description',
                    'type' => 'textarea',
                    'options' => ['lang' => 'fr'],
                    'tab' => 'Général'
                ],
                 // DEVIS
                [   // Custom Field
                    'name' => 'quotes_files',
                    'type' => 'quotes',
                    'project' => $this->project,
                    'title' => 'Devis du Projet',
                    'role' => 'quote',
                    'tab' => 'Devis'

                ],

                // Factures
                [   // Custom Field
                    'name' => 'invoices_files',
                    'type' => 'invoices',
                    'project' => $this->project,
                    'title' => 'Facture du Projet',
                    'role' => 'invoice',
                    'tab' => 'Factures'

                ],
            ],
        );

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
    }
}
