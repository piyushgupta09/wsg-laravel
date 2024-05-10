<?php

namespace Fpaipl\Shopy\Datatables;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Fpaipl\Shopy\Models\Coupon as Model;
use Fpaipl\Panel\Datatables\ModelDatatable;

class CouponDatatable extends ModelDatatable
{
    const SORT_SELECT_DEFAULT = 'created#asc';
    
    public static function baseQuery($model): Builder
    {
        return  $model::query();
    }

    public function selectOptions($field): Collection
    {
        switch ($field) {
            case 'type': return new Collection(collect([
                ['id' => 'percentage', 'name' => 'Percentage'],
                ['id' => 'fixed', 'name' => 'Fixed'],
            ]));
            case 'active': return new Collection(['Yes', 'No']);
            case 'applicable': return new Collection(collect([
                ['id' => 'all', 'name' => 'All Products'],
                ['id' => 'product', 'name' => 'Selected Products'],
                ['id' => 'collection', 'name' => 'Selected Collections'],
                ['id' => 'category', 'name' => 'Selected Categories'],
                ['id' => 'brand', 'name' => 'Selected Brands'],
                ['id' => 'user', 'name' => 'Selected Users'],
                ['id' => 'users', 'name' => 'All Users'],
            ]));
            default: return collect();
        }
    }

    public function topButtons(): array
    {
        return array_merge(
            array(
                'add_new' => [
                    'show' => [
                        'active' => true,
                        'trash' => false,
                    ],
                    'icon' => 'bi bi-plus-lg',
                    'label' => 'Create',
                    'type' => 'buttons.action-link',
                    'style' => '',
                    'route' => 'coupons.create',
                    'function' => ''
                ],
            ),
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
                'route' => 'coupons.show', // categories.show - for new page
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
                'code' => [
                    'name' => 'code',
                    'labels' => [
                        'table' => 'Coupon Code',
                        'export' => 'Coupon Code'
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
                    'importable' => false,
                    'exportable' => [
                        'active' => false,
                        'trash' => false,
                        'value' => 'getTableData'
                    ],
                    'artificial' => false,
                    'fillable' => [
                        'type' => '',
                        'style' => '',
                        'p_style' => 'col-md-4',
                        'placeholder' => '',
                        'component' => 'forms.input-box',
                        'attributes' => ['required'],
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
                        'value' => 'getTableData',
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
                        'active' => false,
                        'trash' => false
                    ],
                    'importable' => false,
                    'exportable' => [
                        'active' => true,
                        'trash' => true,
                        'value' => 'getTableData'
                    ],
                    'artificial' => false,
                    'fillable' => [
                        'type' => 'text',
                        'style' => '',
                        'p_style' => 'col-md-4',
                        'placeholder' => 'Type',
                        'component' => 'forms.select-option',
                        'options' =>  [
                            'data' => self::selectOptions('type'),
                            'withRelation' => true,
                            'relation' => '',
                            'default' => 'fixed',
                        ],
                        'attributes' => ['required'],
                        'rows' => ''
                    ],
                ],
                'applicable' => [
                    'name' => 'applicable',
                    'labels' => [
                        'table' => 'Applicablity',
                        'export' => 'Applicablity'
                    ],
    
                    'thead' => [
                        'view' => 'buttons.sortit',
                        'value' => 'getTableData',
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
                        'active' => false,
                        'trash' => false
                    ],
                    'importable' => false,
                    'exportable' => [
                        'active' => true,
                        'trash' => true,
                        'value' => 'getTableData'
                    ],
                    'artificial' => false,
                    'fillable' => [
                        'type' => 'text',
                        'style' => '',
                        'p_style' => 'col-md-4',
                        'placeholder' => 'Type',
                        'component' => 'forms.select-option',
                        'options' =>  [
                            'data' => self::selectOptions('applicable'),
                            'withRelation' => true,
                            'relation' => '',
                            'default' => 'all',
                        ],
                        'attributes' => ['required'],
                        'rows' => ''
                    ],
                ],
                'value' => [
                    'name' => 'value',
                    'labels' => [
                        'table' => 'Value',
                        'export' => 'Value'
                    ],
    
                    'thead' => [
                        'view' => 'buttons.sortit',
                        'value' => 'getTableData',
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
                        'active' => false,
                        'trash' => false
                    ],
                    'importable' => false,
                    'exportable' => [
                        'active' => true,
                        'trash' => true,
                        'value' => 'getTableData'
                    ],
                    'artificial' => false,
                    'fillable' => [
                        'type' => 'number',
                        'style' => '',
                        'p_style' => 'col-4',
                        'placeholder' => 'Value',
                        'component' => 'forms.input-box',
                        'attributes' => ['required'],
                        'rows' => ''
                    ],
                ],
                'max_value' => [
                    'name' => 'max_value',
                    'labels' => [
                        'table' => 'Max Value',
                        'export' => 'Max Value'
                    ],
    
                    'thead' => [
                        'view' => 'buttons.sortit',
                        'value' => 'getTableData',
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
                        'active' => false,
                        'trash' => false
                    ],
                    'importable' => false,
                    'exportable' => [
                        'active' => true,
                        'trash' => true,
                        'value' => 'getTableData'
                    ],
                    'artificial' => false,
                    'fillable' => [
                        'type' => 'number',
                        'style' => '',
                        'p_style' => 'col-4',
                        'placeholder' => 'Max Value',
                        'component' => 'forms.input-box',
                        'attributes' => ['required'],
                        'rows' => '',
                        'default' => config('settings.max_coupon_value')
                    ],
                ],
                'min_value' => [
                    'name' => 'min_value',
                    'labels' => [
                        'table' => 'Min Value',
                        'export' => 'Min Value'
                    ],
    
                    'thead' => [
                        'view' => 'buttons.sortit',
                        'value' => 'getTableData',
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
                        'active' => false,
                        'trash' => false
                    ],
                    'importable' => false,
                    'exportable' => [
                        'active' => true,
                        'trash' => true,
                        'value' => 'getTableData'
                    ],
                    'artificial' => false,
                    'fillable' => [
                        'type' => 'number',
                        'style' => '',
                        'p_style' => 'col-4',
                        'placeholder' => 'Min Value',
                        'component' => 'forms.input-box',
                        'attributes' => ['required'],
                        'rows' => '',
                        'default' => config('settings.min_coupon_value')
                    ],
                ],
                'max_usage' => [
                    'name' => 'max_usage',
                    'labels' => [
                        'table' => 'Max Usage',
                        'export' => 'Max Usage'
                    ],
    
                    'thead' => [
                        'view' => 'buttons.sortit',
                        'value' => 'getTableData',
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
                        'active' => false,
                        'trash' => false
                    ],
                    'importable' => false,
                    'exportable' => [
                        'active' => true,
                        'trash' => true,
                        'value' => 'getTableData'
                    ],
                    'artificial' => false,
                    'fillable' => [
                        'type' => 'number',
                        'style' => '',
                        'p_style' => 'col-4',
                        'placeholder' => 'Max Usage',
                        'component' => 'forms.input-box',
                        'attributes' => ['required'],
                        'rows' => '',
                        'default' => config('settings.max_coupon_usage')
                    ],
                ],
                'max_usage_per_user' => [
                    'name' => 'max_usage_per_user',
                    'labels' => [
                        'table' => 'Max Usage Per User',
                        'export' => 'Max Usage Per User'
                    ],
    
                    'thead' => [
                        'view' => 'buttons.sortit',
                        'value' => 'getTableData',
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
                        'active' => false,
                        'trash' => false
                    ],
                    'importable' => false,
                    'exportable' => [
                        'active' => true,
                        'trash' => true,
                        'value' => 'getTableData'
                    ],
                    'artificial' => false,
                    'fillable' => [
                        'type' => 'number',
                        'style' => '',
                        'p_style' => 'col-4',
                        'placeholder' => 'Max Usage Per User',
                        'component' => 'forms.input-box',
                        'attributes' => ['required'],
                        'rows' => '',
                        'default' => config('settings.max_coupon_usage_per_user')
                    ],
                ],
                'active' => [
                    'name' => 'active',
                    'labels' => [
                        'table' => 'Active',
                        'export' => 'Active'
                    ],
    
                    'thead' => [
                        'view' => 'buttons.sortit',
                        'value' => 'getTableData',
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
                        'active' => false,
                        'trash' => false
                    ],
                    'importable' => false,
                    'exportable' => [
                        'active' => true,
                        'trash' => true,
                        'value' => 'getTableData'
                    ],
                    'artificial' => false,
                    'fillable' => [
                        'type' => 'checkbox',
                        'style' => '',
                        'placeholder' => 'Activate Coupon',
                        'p_style' => 'col-4',
                        'component' => 'forms.radio-option',
                        'options' =>  [
                            'data' => self::selectOptions('active'),
                            'withRelation' => false,
                            'relation' => '',
                            'default' => 0,
                        ],
                        'attributes' => ['required'],
                        'rows' => '',
                    ],
                ],
                'valid_from' => [
                    'name' => 'valid_from',
                    'labels' => [
                        'table' => 'Valid From',
                        'export' => 'Valid From'
                    ],
    
                    'thead' => [
                        'view' => 'buttons.sortit',
                        'value' => 'getTableData',
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
                        'active' => false,
                        'trash' => false
                    ],
                    'importable' => false,
                    'exportable' => [
                        'active' => true,
                        'trash' => true,
                        'value' => 'getTableData'
                    ],
                    'artificial' => false,
                    'fillable' => [
                        'type' => 'date',
                        'style' => '',
                        'p_style' => 'col-6',
                        'placeholder' => 'Valid From',
                        'component' => 'forms.input-box',
                        'attributes' => ['required'],
                        'rows' => '',
                        'default' => date('Y-m-d')
                    ],
                ],
                'valid_to' => [
                    'name' => 'valid_to',
                    'labels' => [
                        'table' => 'Valid To',
                        'export' => 'Valid To'
                    ],
    
                    'thead' => [
                        'view' => 'buttons.sortit',
                        'value' => 'getTableData',
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
                        'active' => false,
                        'trash' => false
                    ],
                    'importable' => false,
                    'exportable' => [
                        'active' => true,
                        'trash' => true,
                        'value' => 'getTableData'
                    ],
                    'artificial' => false,
                    'fillable' => [
                        'type' => 'date',
                        'style' => '',
                        'p_style' => 'col-6',
                        'placeholder' => 'Valid To',
                        'component' => 'forms.input-box',
                        'attributes' => ['required'],
                        'rows' => '',
                        'default' => date('Y-m-d', strtotime(date('Y-m-d') . ' + 30 days'))
                    ],
                ],
               
                'detail' => [
                    'name' => 'detail',
                    'labels' => [
                        'table' => 'Detail',
                        'export' => 'Detail'
                    ],
    
                    'thead' => [
                        'view' => 'buttons.sortit',
                        'value' => 'getTableData',
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
                        'active' => false,
                        'trash' => false
                    ],
                    'importable' => false,
                    'exportable' => [
                        'active' => true,
                        'trash' => true,
                        'value' => 'getTableData'
                    ],
                    'artificial' => false,
                    'fillable' => [
                        'type' => 'textarea',
                        'style' => '',
                        'placeholder' => 'Detail',
                        'component' => 'forms.input-box',
                        'attributes' => [],
                        'rows' => ''
                    ],
                ],
            ),
            parent::getDefaultPostColumns(),
        );
    }

}