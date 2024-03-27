{{-- 
   $label : It ia used as label 
   $model :  It ia a model object
   $name : It is field name of table of database
--}}
{{-- @if(!empty($label) && !empty($model->$name))
    {{ $label }} :
@endif --}}
@if (!empty($model->$name))    
    <div class="text-{{ $align }}">
        <div>
            @if(method_exists($model, 'getDatetimeString'))
                {{ $model->getDatetimeString($name) }}
            @elseif(is_object($model->$name))
                {{ date_format($model->$name, 'M d, Y') }}
            @else
                {{ date_format(date_create($model->$name), 'M d, Y') }}
            @endif
        </div>
    </div>
@endif
