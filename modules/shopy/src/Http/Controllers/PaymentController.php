<?php

namespace Fpaipl\Shopy\Http\Controllers;

use Illuminate\Http\Request;
use Fpaipl\Panel\Http\Controllers\PanelController;
use Fpaipl\Shopy\Datatables\PaymentDatatable as Datatable;

class PaymentController extends PanelController
{
    public function __construct()
    {
        parent::__construct(
            new Datatable(), 
            'Fpaipl\Shopy\Models\Payment', 
            'payment', 'payments.index'
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