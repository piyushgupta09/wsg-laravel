<?php

namespace Fpaipl\Shopy\Datatables;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Fpaipl\Shopy\Models\Delivery as Model;
use Fpaipl\Panel\Datatables\ModelDatatable;

class DeliveryDatatable extends ModelDatatable
{
    const SORT_SELECT_DEFAULT = 'id#desc';

    /**
     * It is used to store batch uuid in cache with in this key.
     */
    const IMPORT_BATCH_UUID = 'Delivery_batch_uuid';

    
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
                'route' => 'deliveries.show', // categories.show - for new page
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

    public function getColumns(): array
    {
        return array_merge(
            parent::getDefaultPreColumns(),
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
                        'value' => '',
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
                'type' => [
                    'name' => 'type',
                    'labels' => [
                        'table' => 'Type',
                        'export' => 'Type'
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
                        'value' => 'getTableData'
                    ],
                    'artificial' => false,
                    'fillable' => [
                        'type' => 'text',
                        'style' => '',
                        'placeholder' => 'Type',
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