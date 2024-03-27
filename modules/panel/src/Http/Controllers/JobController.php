<?php

namespace Fpaipl\Panel\Http\Controllers;

use Fpaipl\Panel\Http\Controllers\PanelController;
use Fpaipl\Panel\Datatables\JobDatatable as Datatable;

class JobController extends PanelController
{
    public function __construct()
    {
        $this->middleware('role:admin');

        parent::__construct(
            new Datatable(), 
            'Fpaipl\Panel\Models\Job', 
            'job', 'jobs.index'
        );
    }
}