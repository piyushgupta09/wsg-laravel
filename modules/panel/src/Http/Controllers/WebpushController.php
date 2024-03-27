<?php

namespace Fpaipl\Panel\Http\Controllers;

use Fpaipl\Panel\Http\Controllers\PanelController;
use Fpaipl\Panel\Datatables\WebpushDatatable as Datatable;

class WebpushController extends PanelController
{
    public function __construct()
    {
        $this->middleware('role:admin');

        parent::__construct(
            new Datatable(), 
            'Fpaipl\Panel\Models\Webpush', 
            'webpush', 'webpushes.index'
        );
    }
}