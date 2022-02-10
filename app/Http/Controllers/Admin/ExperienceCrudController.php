<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ExperienceRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class ExperienceCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class ExperienceCrudController extends CrudController
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
        CRUD::setModel(\App\Models\Experience::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/experience');
        CRUD::setEntityNameStrings('experience', 'experiences');
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
                'label' => "Photo", // Table column heading
                'type' => 'image',
                'prefix' => 'storage/experiences/',
                'height' => '50px',
                'weight'=> '50px',
            ],
            [
                'name' => 'location',
                'label' => 'Lieux'
            ],
            [
                'name' => 'name',
                'label' => "Nom de l'expérience"
            ],
            [
                'name'  => 'from', // The db column name
                'label' => 'De', // Table column heading
                'type'  => 'date',
                // 'format' => 'l j F Y', // use something else than the base.default_date_format config value
            ],
            [
                'name'  => 'to', // The db column name
                'label' => 'A', // Table column heading
                'type'  => 'date',
                // 'format' => 'l j F Y', // use something else than the base.default_date_format config value
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
        CRUD::setValidation(ExperienceRequest::class);
        $this->crud->addFields([
            [
                'label' => "Image",
                'name' => "logo",
                'type' => 'image',
                'disk' => 'public',
                'prefix' => 'experiences/',
                'crop' => true, // set to true to allow cropping, false to disable
                'tab' => 'Général',
            ],
            [
                'name' => 'location',
                'label' => 'Lieux',
                'type' => 'text',
                'tab' => 'Général',
                'wrapper' => ['class' => 'form-group col-md-6'],

            ],
            [
                'name' => 'name',
                'label' => "Nom de l'expérience",
                'type' => 'text',
                'tab' => 'Général',
                'wrapper' => ['class' => 'form-group col-md-6'],

            ],
            [   // date_picker
                'name'  => 'from',
                'type'  => 'date_picker',
                'label' => 'De',
                'tab' => 'Général',
             
                // optional:
                'date_picker_options' => [
                   'todayBtn' => 'linked',
                   'format'   => 'dd-mm-yyyy',
                   'language' => 'fr'
                ],
            ],
            [   // date_picker
                'name'  => 'to',
                'type'  => 'date_picker',
                'label' => 'A',
                'tab' => 'Général',
             
                // optional:
                'date_picker_options' => [
                   'todayBtn' => 'linked',
                   'format'   => 'dd-mm-yyyy',
                   'language' => 'fr'
                ],
            ],
            [
                'name' => 'notes',
                'label' => 'Notes',
                'type' => 'textarea',
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
    }
}
