<?php

namespace Fpaipl\Shopy\Http\Controllers;

use Illuminate\Http\Request;
use Fpaipl\Panel\Http\Controllers\PanelController;
use Fpaipl\Shopy\Datatables\DeliveryDatatable as Datatable;

class DeliveryController extends PanelController
{
    public function __construct()
    {
        parent::__construct(
            new Datatable(), 
            'Fpaipl\Shopy\Models\Delivery', 
            'delivery', 'deliveries.index'
        );
    }

    public function store(Request $request)
    {
        $this->methodNotAllowed($request);
    }

    public function update(Request $request)
    {
        $this->methodNotAllowed($request);
    }

    public function destroy(Request $request)
    {
        $this->methodNotAllowed($request);
    }
}
