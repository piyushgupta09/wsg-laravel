<?php

namespace Fpaipl\Shopy\Http\Controllers;

use Fpaipl\Panel\Http\Controllers\PanelController;
use Fpaipl\Shopy\Datatables\NewOrderDatatable as Datatable;

class NewOrderController extends PanelController
{
    public function __construct()
    {
        parent::__construct(
            new Datatable(), 
            'Fpaipl\Shopy\Models\Order', 
            'Order', 'new-orders.index'
        );
    }
}
