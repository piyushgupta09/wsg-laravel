<?php

namespace Fpaipl\Authy\Datatables;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Fpaipl\Authy\Models\Account as Model;
use Fpaipl\Panel\Datatables\ModelDatatable;

class NewAccountDatatable extends ModelDatatable
{
    const SORT_SELECT_DEFAULT = 'updated_at#desc';
    
    public static function baseQuery($model): Builder
    {
        return $model::query()->newAccounts();
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
        );
    }

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