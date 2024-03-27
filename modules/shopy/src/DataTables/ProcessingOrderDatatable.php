<?php

namespace Fpaipl\Shopy\DataTables;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Fpaipl\Shopy\Models\Order as Model;
use Fpaipl\Panel\Datatables\ModelDatatable;

class ProcessingOrderDatatable extends OrderDatatable
{
    public static function baseQuery($model): Builder
    {
        return $model::query()->processing();
    }
}
