{{-- 
   $name : The 'name' attribute for the input field in HTML.
   $model : The instance of the model. If provided, the model's corresponding value will be set as the input value.
   @error($name) : Laravel Blade directive. If there is a validation error for this input field, the error message is displayed.
--}}

<input 
    type="hidden"
    name="{{ $name }}"
    id="hidden{{ $modelName }}{{ $name }}"
    value="{{ empty($model) ? old($name) : $model->$name }}"
>