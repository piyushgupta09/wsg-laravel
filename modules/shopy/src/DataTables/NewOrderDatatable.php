<?php

namespace Fpaipl\Shopy\DataTables;

use Illuminate\Database\Eloquent\Builder;

class NewOrderDatatable extends OrderDatatable
{
    public static function baseQuery($model): Builder
    {
        return $model::query()->pending();
    }
}