{{-- 
   $label : It is used as label
   $model : It is a model object
   $name : It is the field name of the table in the database
   $align : It is used for text alignment
--}}

<div class="text-{{ $align }}">
    <div>
        @if (isset($model->$name))
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" id="{{ $name }}" {{ $model->$name ? 'checked' : '' }} disabled>
                <label class="form-check-label" for="{{ $name }}">
                    {{ $label ?? ucfirst($name) }}
                </label>
            </div>
        @else
            <span>No Data</span>
        @endif
    </div>
</div>
