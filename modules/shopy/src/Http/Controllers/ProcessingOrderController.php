<?php

namespace Fpaipl\Shopy\Http\Controllers;

use Illuminate\Http\Request;
use Fpaipl\Panel\Http\Controllers\PanelController;
use Fpaipl\Shopy\DataTables\ProcessingOrderDatatable as Datatable;

class ProcessingOrderController extends PanelController
{
    public function __construct()
    {
        parent::__construct(
            new Datatable(), 
            'Fpaipl\Shopy\Models\Order', 
            'Order', 'orders.index'
        );
    }

    public function __invoke(Request $request)
    {
        return $this->index($request);
    }
}
