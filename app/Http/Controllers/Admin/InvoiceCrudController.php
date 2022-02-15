<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\InvoiceRequest;
use App\Models\Invoice;
use App\Models\Client;
use App\Models\Project;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class InvoiceCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class InvoiceCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;


    public function fetchClient()
    {
        return Client::where('name', 'like', '%' . request()->input('q') . '%')->orWhereHas('parent', function ($q) {
            $q->where('name', 'like', '%' . request()->input('q') . '%');
        })->orderBy('name')->get()->each->append('name_with_group_marker');
    }


    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Invoice::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/invoice');
        CRUD::setEntityNameStrings('Facture', 'Factures');
        //todo eager loading
        $this->crud->denyAccess('create');
        $this->crud->denyAccess('delete');
        $this->crud->denyAccess('show');

     

        $this->crud->addButtonFromView('line', 'pdf', 'get_billing_pdf', 'end');

        $this->crud->billing_route = "admin/api/invoices";

        $this->invoice = ($invoiceId = \Route::current()->parameter('id')) ? Invoice::whereId($invoiceId)->with('project.client')->first() : null;
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
                'name' => 'status',
                'label' => 'Status',
                'type' => 'select_from_array',
                'options' => Invoice::getAllStatus(),
                'wrapper' => [
                    'element' => 'div',
                    'class' => function ($crud, $column, $entry, $related_key) {

                        if ($entry['status'] == 'paid') {
                            return 'badge badge-success';
                        }
                        if ($entry['status'] == 'partially paid') {
                            return 'badge badge-warning';
                        }
                        if ($entry['status'] == 'waiting for payment') {
                            return 'badge badge-warning';
                        }
                        if ($entry['status'] == 'unpaid invoice') {
                            return 'badge badge-dark';
                        }
                        return 'badge badge-default';
                    },
                ],
                'searchLogic' => false,
            ],
            [
                'name' => 'number',
                'label' => 'No Facture',
                'type' => 'text',
                'searchLogic' => function ($query, $column, $searchTerm) {
                    $query->orWhere('number', 'LIKE', $searchTerm . '%');
                }
            ],
            [
                'name' => 'project.project_number',
                'label' => 'No projet',
                'type' => 'text',
                'searchLogic' => function ($query, $column, $searchTerm) {
                    $query->orWhereHas('project', function ($q) use ($column, $searchTerm) {
                        $q->where('project_number', 'LIKE', '%' . $searchTerm . '%');
                    });
                }
            ],
            [
                'name' => 'project.client.name',
                'label' => 'Client',
                'type' => 'text',
                'searchLogic' => function ($query, $column, $searchTerm) {
                    $query->orWhereHas('project', function ($q) use ($column, $searchTerm) {
                        $q->whereHas('client', function ($q) use ($column, $searchTerm) {
                            $q->where('name', 'LIKE', '%' . $searchTerm . '%');
                        });
                    });
                }
            ],
            [
                'name' => 'creation_date',
                'label' => 'Date',
                'type' => 'date',
            ],
            [
                'name' => 'validity_date',
                'label' => 'Échéance',
                'type' => 'date',
            ],
            [
                'name' => 'sell_total',
                'label' => 'Montant HT',
                'type' => 'number',
                'suffix' => ' €',
                'decimals' => 2,
                'dec_point' => ',',
                'thousands_sep' => ' ',

            ],
            [
                'name' => 'sell_total_ttc',
                'label' => 'Montant TTC',
                'type' => 'number',
                'suffix' => ' €',
                'decimals' => 2,
                'dec_point' => ',',
                'thousands_sep' => ' ',
                'searchLogic' => function ($query, $column, $searchTerm) {
                    $query->orWhere('sell_total_ttc', 'LIKE', '%' . $searchTerm . '%');
                }
            ]
        ]);


        // $this->crud->addFilter(
        //     [
        //         'name' => 'status',
        //         'type' => 'select2',
        //         'label' => 'Statut'
        //     ],
        //     function () {
        //         return Invoice::getAllStatus();
        //     },
        //     function ($value) { // if the filter is active
        //         $this->crud->addClause('where', 'status', '=', $value);
        //     }
        // );


        // // dropdown filter
        // $this->crud->addFilter(
        //     [
        //         'name' => 'client',
        //         'type' => 'select2',
        //         'label' => 'Client'
        //     ],
        //     function () {
        //         return Client::orderBy('name')->get()->pluck('name', 'id')->toArray();
        //     },
        //     function ($value) { // if the filter is active
        //         //$this->crud->addClause('where', 'client_id', '=', $value);
        //         $this->crud->query->whereHas('project', function ($project) use ($value) {
        //             $project->whereHas('client', function ($client) use ($value) {
        //                 $client->where('id', $value);
        //             });
        //         });
        //     }
        // );

        // $this->crud->addFilter(
        //     [
        //         'name' => 'project_type',
        //         'type' => 'select2',
        //         'label' => 'Type de projet'
        //     ],
        //     function () {
        //         return Project::getAllTypes();
        //     },
        //     function ($value) { // if the filter is active
        //         $this->crud->query->whereHas('project', function ($project) use ($value) {

        //             $project->where('project_type', $value);

        //         });
        //     }
        // );

        // $this->crud->addFilter([
        //     'type' => 'date_range',
        //     'name' => 'creation_date',
        //     'label' => 'Date de création'
        // ],
        //     false,
        //     function ($value) { // if the filter is active, apply these constraints
        //         $dates = json_decode($value);
        //         $this->crud->addClause('where', 'creation_date', '>=', $dates->from);
        //         $this->crud->addClause('where', 'creation_date', '<=', $dates->to . ' 23:59:59');
        //     });


        // $this->crud->addFilter([
        //     'type' => 'date_range',
        //     'name' => 'validity_date',
        //     'label' => 'Échéance'
        // ],
            // false,
            // function ($value) { // if the filter is active, apply these constraints
            //     $dates = json_decode($value);
            //     $this->crud->addClause('where', 'validity_date', '>=', $dates->from);
            //     $this->crud->addClause('where', 'validity_date', '<=', $dates->to . ' 23:59:59');
            // });


    }

    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(InvoiceRequest::class);

        CRUD::field('project_id');
        CRUD::field('address_id');
        CRUD::field('number');
        CRUD::field('statut');
        CRUD::field('creation_date');
        CRUD::field('validity_date');
        CRUD::field('agefiph_total');
        CRUD::field('notes');
        CRUD::field('discount_euro');
        CRUD::field('discount_unit');
        CRUD::field('sell_total');

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
        $this->crud->addField(
            [
                'name' => 'status',
                'label' => 'Status',
                'type' => 'select_from_array',
                'options' => Invoice::getAllStatus()
            ],
        );

    }
}
