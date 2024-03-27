<?php

namespace Fpaipl\Panel\Http\Controllers;

use Fpaipl\Panel\Http\Controllers\PanelController;
use Fpaipl\Panel\Datatables\ActivitylogDatatable as Datatable;

class ActivitylogController extends PanelController
{
    public function __construct()
    {
        $this->middleware('role:admin');

        parent::__construct(
            new Datatable(), 
            'Fpaipl\Panel\Models\Activitylog', 
            'activitylog', 'activitylogs.index'
        );
    }
}