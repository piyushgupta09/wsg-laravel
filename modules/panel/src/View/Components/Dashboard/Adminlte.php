<?php

namespace Fpaipl\Panel\View\Components\Dashboard;

use Closure;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;

class Adminlte extends Component
{
      /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('panel::components.dashboard.adminlte');
    }
}
