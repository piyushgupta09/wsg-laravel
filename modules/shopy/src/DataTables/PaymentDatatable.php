<?php

namespace Fpaipl\Shopy\Datatables;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Fpaipl\Shopy\Models\Payment as Model;
use Fpaipl\Panel\Datatables\ModelDatatable;

class PaymentDatatable extends ModelDatatable
{
    const SORT_SELECT_DEFAULT = 'order_id#asc';

    const IMPORT_BATCH_UUID = 'Payment_batch_uuid';

    public static function baseQuery($model): Builder
    {
        return $model::query();
    }

    public function selectOptions($field): Collection
    {
        switch ($field) {
            default: return collect();
                // return collect([
                //     (object) [
                //         'id' => 'twitter',
                //         'name' => 'twitter.com'
                //     ],
                //     (object) [
                //         'id' => 'google',
                //         'name' => 'google.com'
                //     ]
                // ]);
        }
    }

    public function topButtons(): array
    {
        return array_merge(
            array(
               
            ),
            //parent::topButtonsPart1(),
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
                'route' => 'payments.show', // categories.show - for new page
                'function' => '',
                'confirm' => false, // This boolean value control that confirm modal will show or not
            ],
           
            // 'delete' => [
            //     'show' => [
            //         'active' => $this->features()['row_actions']['show']['delete'],
            //         'trash' => false, //Will always be false because we can't delete on trash page.
            //     ],
            //     'label' => 'Delete',
            //     'icon' => 'bi bi-trash',
            //     'type' => 'buttons.action-delete',
            //     'style' => '',
            //     'route' => 'orders.destroy',
            //     'function' => '',
            //     'confirm' => false, // To open confirm mode, we have to set  type' => 'buttons.action-btn' and 'confirm' => true
            // ],
            // 'adv_delete' => [
            //     'show' => [
            //         'active' => true,
            //         'trash' => false, //Will always be false because we can't delete on trash page.
            //     ],
            //     'label' => 'Adv Delete',
            //     'icon' => 'bi bi-shield-x',
            //     'type' => 'buttons.action-link',
            //     'style' => '',
            //     'route' => 'orders.advance.delete',
            //     'function' => '',
            //     'confirm' => false, // To open confirm mode, we have to set  type' => 'buttons.action-btn' and 'confirm' => true
            // ]
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
     *     'artificial' => boolean, // This control that this field will show or not in Add/Edit page.
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
            array(
                'order_id' => [
                    'name' => 'order_id',
                    'labels' => [
                        'table' => 'Order Id',
                        'export' => 'Order Id'
                    ],
                    'thead' => [
                        'view' => 'buttons.sortit',
                        'value' => '',
                        'align' => '',
                    ],
                    'tbody' => [
                        'view' => 'cells.text-value',
                        'value' => 'getTableData',
                        'align' => '',
                    ],
                    'viewable' => [
                        'active' => true,
                        'trash' => true
                    ],
                    'expandable' => [
                        'active' => true,
                        'trash' => false
                    ],
                    'sortable' => true,
                    'filterable' => [
                        'active' => true,
                        'trash' => false
                    ],
                    'importable' => true,
                    'exportable' => [
                        'active' => true,
                        'trash' => true,
                        'value' => 'getTableData'
                    ],
                    'artificial' => false,
                    'fillable' => [
                        'type' => '',
                        'style' => '',
                        'placeholder' => '',
                        'component' => 'forms.input-box',
                        'attributes' => ['autofocus'],
                        'rows' => ''
                    ],
                ],
                'mode' => [
                    'name' => 'mode',
                    'labels' => [
                        'table' => 'Mode',
                        'export' => 'Mode'
                    ],
    
                    'thead' => [
                        'view' => 'buttons.sortit',
                        'value' => '',
                        'align' => '',
                    ],
                    'tbody' => [
                        'view' => 'cells.text-value',
                        'value' => 'getTableData',
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
                        'value' => 'getTableData'
                    ],
                    'artificial' => false,
                    'fillable' => [
                        'type' => 'text',
                        'style' => '',
                        'placeholder' => 'Mode',
                        'component' => 'forms.input-box',
                        'attributes' => ['required'],
                        'rows' => ''
                    ],
    
    
                ],
                'reference_id' => [
                    'name' => 'reference_id',
                    'labels' => [
                        'table' => 'Reference Id',
                        'export' => 'Reference Id'
                    ],
    
                    'thead' => [
                        'view' => 'buttons.sortit',
                        'value' => '',
                        'align' => '',
                    ],
                    'tbody' => [
                        'view' => 'cells.text-value',
                        'value' => 'getTableData',
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
                        'value' => 'getTableData'
                    ],
                    'artificial' => false,
                    'fillable' => [
                        'type' => 'text',
                        'style' => '',
                        'placeholder' => 'Reference Id',
                        'component' => 'forms.input-box',
                        'attributes' => ['required'],
                        'rows' => ''
                    ],
    
    
                ],
                'amount' => [
                    'name' => 'amount',
                    'labels' => [
                        'table' => 'Amount',
                        'export' => 'Amount'
                    ],
    
                    'thead' => [
                        'view' => 'buttons.sortit',
                        'value' => '',
                        'align' => '',
                    ],
                    'tbody' => [
                        'view' => 'cells.text-value',
                        'value' => 'getTableData',
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
                        'value' => 'getTableData'
                    ],
                    'artificial' => false,
                    'fillable' => [
                        'type' => 'text',
                        'style' => '',
                        'placeholder' => 'Amount',
                        'component' => 'forms.input-box',
                        'attributes' => ['required'],
                        'rows' => ''
                    ],
    
    
                ],
                'date' => [
                    'name' => 'date',
                    'labels' => [
                        'table' => 'Date',
                        'export' => 'Date'
                    ],
    
                    'thead' => [
                        'view' => 'buttons.sortit',
                        'value' => '',
                        'align' => '',
                    ],
                    'tbody' => [
                        'view' => 'cells.date-value',
                        'value' => 'getTableData',
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
                        'value' => 'getTableData'
                    ],
                    'artificial' => false,
                    'fillable' => [
                        'type' => 'text',
                        'style' => '',
                        'placeholder' => 'Date',
                        'component' => 'forms.input-box',
                        'attributes' => ['required'],
                        'rows' => ''
                    ],
    
    
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
                        'value' => 'getTableData',
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
                        'value' => 'getTableData'
                    ],
                    'artificial' => false,
                    'fillable' => [
                        'type' => 'text',
                        'style' => '',
                        'placeholder' => 'Status',
                        'component' => 'forms.input-box',
                        'attributes' => ['required'],
                        'rows' => ''
                    ],
    
    
                ],
            ),
            parent::getDefaultPostColumns(),
        );
    }

}