<?php

namespace Fpaipl\Authy\Datatables;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Fpaipl\Authy\Models\Account as Model;
use Fpaipl\Panel\Datatables\ModelDatatable;

class AccountDatatable extends ModelDatatable
{
    const SORT_SELECT_DEFAULT = 'status#desc';
    
    /**
     * It is used to store batch uuid in cache with in this key.
     */
    const IMPORT_BATCH_UUID = 'User_batch_uuid';

    
    public static function baseQuery($model): Builder
    {
        return $model::query();
    }

    public function selectOptions($field): Collection
    {
        switch ($field) {
            case 'status': return new Collection(collect(Model::STATUS)->all());
            default: return collect();
        }
    }

    public function topButtons(): array
    {
        return array_merge(
            parent::topButtonsPart2()  
        );
    }

    public function tableButtons(): array
    {
        return array(
            'view' => [
                'show' => [
                    'active' => $this->features()['row_actions']['show']['view']['active'],
                    'trash' => $this->features()['row_actions']['show']['view']['trash'],
                ],
                'label' => 'View',
                'icon' => 'bi bi-eye',
                'type' => 'buttons.action-link', // action-link - for new page && action-toggle to collapse
                'style' => '',
                'route' => 'accounts.show', // categories.show - for new page
                'function' => '',
                'confirm' => false, // This boolean value control that confirm modal will show or not
            ],
           
            'delete' => [
                'show' => [
                    'active' => $this->features()['row_actions']['show']['delete'],
                    'trash' => false, //Will always be false because we can't delete on trash page.
                ],
                'label' => 'Delete',
                'icon' => 'bi bi-trash',
                'type' => 'buttons.action-delete',
                'style' => '',
                'route' => 'accounts.destroy',
                'function' => '',
                'confirm' => false, // To open confirm mode, we have to set  type' => 'buttons.action-btn' and 'confirm' => true
            ],
            
        );
    }

    /**
     *  'key_name' => 
     *   [
     *     'name' => 'string', // Field name of database's table
     *     'labels' => [
     *         'table' => 'string', // Used as a label of field in list , add, edit page.  
     *         'export' => 'string' // Used as a label of field in excel file of export.
     *     ],
     *     'cell' => [
     *         'view' => 'string', // It denotes the component name used to show the field value on list and show page. 
     *         'function' => 'string' // If we provide value in it then this value will be used as a function name to get the value.
     *     ],
     *     'viewable' => [
     *         'active' => boolean, // This control that this field will show or not on list page.
     *         'trash' => boolean // This control that this field will show or not on trash page.
     *     ],
     *     'expandable' => [
     *         'active' => boolean, // This control that this field will show or not on list page in expandable section.
     *         'trash' => boolean // This control that this field will show or not on trash page in expandable section.
     *     ],
     *     'sortable' => boolean, // This control that sortable select section (like Asc or Desc) with this field will be created or not.
     *     'filterable' => [
     *         'active' => boolean, // This control that this field will show on not in filter section on list page
     *         'trash' => boolean // This control that this field will show on not in filter section on trash page
     *     ],
     *     'exportable' => [ // This control that this field will show or not in excel file of export.
     *         'active' => boolean,
     *         'trash' => boolean
     *     ],
     *     'artificial' => boolean, // This control that this field will show or not in Add/Edit/Show page.
     *     'fillable' => [
     *         'type' => 'string', // Used as a type of input field for Add/Edit page.
     *         'style' => 'string', // Used as a class name in input field
     *         'placeholder' => 'string', // Used as a placeholder value of input field.
     *         'component' => 'string', // Used for input field creation for Add/Edit page.
     *         'attributes' => ['string'] // Used as attributes of input field.
     *     ],
     *     'showable' => boolean, // This control that this field will show or not in view page when view is enable for route
     *  ],
     * 
     */
    public function getColumns(): array
    {
        return array_merge(
            parent::getDefaultPreColumns(),
            array(
                'name' => [
                    'name' => 'name',
                    'labels' => [
                        'table' => 'Name',
                        'export' => 'Name'
                    ],
    
                    'thead' => [
                        'view' => 'buttons.sortit',
                        'value' => '',
                        'align' => '',
                    ],
                    'tbody' => [
                        'view' => 'cells.text-value',
                        'value' => '',
                        'align' => '',
                    ],
                    'viewable' => [
                        'active' => true,
                        'trash' => true
                    ],
                    'expandable' => [
                        'active' => false,
                        'trash' => false
                    ],
                    'sortable' => true,
                    'filterable' => [
                        'active' => true,
                        'trash' => true
                    ],
                    'importable' => true,
                    'exportable' => [
                        'active' => true,
                        'trash' => true,
                        'value' => 'getValue'
                    ],
                    'artificial' => true,
                    'fillable' => [],
                ],
                'application_id' => [
                    'name' => 'application_id',
                    'labels' => [
                        'table' => 'Application Id',
                        'export' => 'Application Id'
                    ],
    
                    'thead' => [
                        'view' => 'buttons.sortit',
                        'value' => '',
                        'align' => '',
                    ],
                    'tbody' => [
                        'view' => 'cells.text-value',
                        'value' => '',
                        'align' => '',
                    ],
                    'viewable' => [
                        'active' => true,
                        'trash' => true
                    ],
                    'expandable' => [
                        'active' => false,
                        'trash' => false
                    ],
                    'sortable' => true,
                    'filterable' => [
                        'active' => true,
                        'trash' => true
                    ],
                    'importable' => true,
                    'exportable' => [
                        'active' => true,
                        'trash' => true,
                        'value' => 'getValue'
                    ],
                    'artificial' => true,
                    'fillable' => [],
                ],
                'status' => [
                    'name' => 'status',
                    'labels' => [
                        'table' => 'Status',
                        'export' => 'Status'
                    ],
                    'thead' => [
                        'view' => 'buttons.sortit',
                        'value' => '',
                        'align' => '',
                    ],
                    'tbody' => [
                        'view' => 'cells.text-value',
                        'value' => '',
                        'align' => '',
                    ],
                    'viewable' => [
                        'active' => true,
                        'trash' => true
                    ],
                    'expandable' => [
                        'active' => false,
                        'trash' => false
                    ],
                    'sortable' => true,
                    'filterable' => [
                        'active' => true,
                        'trash' => true
                    ],
                    'importable' => true,
                    'exportable' => [
                        'active' => true,
                        'trash' => true,
                        'value' => 'getValue'
                    ],
                    'artificial' => true,
                    'fillable' => [],
                ],
                'created_at' => [
                    'name' => 'created_at',
                    'labels' => [
                        'table' => 'Created At',
                        'export' => 'Created At'
                    ],
                    'viewable' => [
                        'active' => true,
                        'trash' => false
                    ],
                    'expandable' => [
                        'active' => false,
                        'trash' => true
                    ],
                    'sortable' => true,
                    'thead' => [
                        'view' => 'buttons.sortit',
                        'value' => '',
                        'align' => '',
                    ],
                    'tbody' => [
                        'view' => 'cells.date-value',
                        'value' => '',
                        'align' => '',
                    ],
                    'importable' => false,
                    'exportable' => [
                        'active' => true,
                        'trash' => true,
                        'value' => 'getTimestamp'
                    ],
                    'filterable' => [
                        'active' => true,
                        'trash' => false
                    ],
                    'artificial' => true,
                ],
                'updated_at' => [
                    'name' => 'updated_at',
                    'labels' => [
                        'table' => 'Updated At',
                        'export' => 'Updated At'
                    ],
                    'viewable' => [
                        'active' => false,
                        'trash' => false
                    ],
                    'expandable' => [
                        'active' => false,
                        'trash' => true
                    ],
                    'sortable' => true,
                    'thead' => [
                        'view' => 'buttons.sortit',
                        'value' => '',
                        'align' => '',
                    ],
                    'tbody' => [
                        'view' => 'cells.date-value',
                        'value' => '',
                        'align' => '',
                    ],
                    'importable' => false,
                    'exportable' => [
                        'active' => true,
                        'trash' => false,
                        'value' => 'getTimestamp'
                    ],
                    'filterable' => [
                        'active' => true,
                        'trash' => false
                    ],
                    'artificial' => true,
    
                ],
                'deleted_at' => [
                    'name' => 'deleted_at',
                    'labels' => [
                        'table' => 'Deleted At',
                        'export' => 'Deleted At'
                    ],
                    'viewable' => [
                        'active' => false,
                        'trash' => false
                    ],
                    'expandable' => [
                        'active' => false, // will always be false
                        'trash' => false
                    ],
                    'sortable' => true,
                    'thead' => [
                        'view' => 'buttons.sortit',
                        'value' => '',
                        'align' => '',
                    ],
                    'tbody' => [
                        'view' => 'cells.date-value',
                        'value' => '',
                        'align' => '',
                    ],
                    'importable' => false,
                    'exportable' => [
                        'active' => false,
                        'trash' => true,
                        'value' => 'getTimestamp'
                    ],
                    'filterable' => [
                        'active' => false,
                        'trash' => true
                    ],
                    'artificial' => true,
    
    
                ]
            ),
        );
    }

}