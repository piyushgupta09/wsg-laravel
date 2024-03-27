<?php

namespace Fpaipl\Panel\Datatables;

use Fpaipl\Panel\Models\Activitylog as Model;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder;
use Fpaipl\Panel\Datatables\ModelDatatable;

class ActivitylogDatatable extends ModelDatatable
{
    const SORT_SELECT_DEFAULT = 'created_at#desc';
    
    public static function baseQuery($model): Builder
    {
        return $model::query();
    }

    public function selectOptions($field): Collection
    {
        switch ($field) {
            default: return new Collection(collect());
        }
    }

    public function topButtons(): array
    {
        return array();
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
                'icon' => 'bi bi-chevron-right',
                'type' => 'buttons.action-link',
                'style' => '',
                'route' => 'activitylogs.show',
                'function' => '',
                'confirm' => false,
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
                    'artificial' => true,
                ],
                'description' => [
                    'name' => 'description',
                    'labels' => [
                        'table' => 'Description',
                        'export' => 'Description'
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
                    'artificial' => true,
                ],
                'subject' => [
                    'name' => 'subject',
                    'labels' => [
                        'table' => 'Subject',
                        'export' => 'Subject'
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
                    'artificial' => true,
                ],
                'causer' => [
                    'name' => 'causer',
                    'labels' => [
                        'table' => 'Causer',
                        'export' => 'Causer'
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
                    'artificial' => true,
                ],
                'event' => [
                    'name' => 'event',
                    'labels' => [
                        'table' => 'Event',
                        'export' => 'Event'
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
                    'artificial' => true,
                ],
                'properties' => [
                    'name' => 'properties',
                    'labels' => [
                        'table' => 'Properties',
                        'export' => 'Properties'
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
                        'trash' => false
                    ],
                    'expandable' => [
                        'active' => true,
                        'trash' => true
                    ],
                    'sortable' => false,
                    'filterable' => [
                        'active' => false,
                        'trash' => false
                    ],
                    'importable' => false,
                    'exportable' => [
                        'active' => false,
                        'trash' => false,
                        'value' => 'getTableData'
                    ],
                    'artificial' => true,
                ],
                'batch_uuid' => [
                    'name' => 'batch_uuid',
                    'labels' => [
                        'table' => 'Batch Uuid',
                        'export' => 'Batch Uuid'
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
                        'active' => false,
                        'trash' => false
                    ],
                    'expandable' => [
                        'active' => true,
                        'trash' => true
                    ],
                    'sortable' => false,
                    'filterable' => [
                        'active' => false,
                        'trash' => false
                    ],
                    'importable' => false,
                    'exportable' => [
                        'active' => false,
                        'trash' => false,
                        'value' => 'getTableData'
                    ],
                    'artificial' => true,
                ],
            ),
            parent::getDefaultPostColumns(),
        );
    }
}