{{--
    $modelName : This variable contains the model name and is used for generating unique IDs for input fields.
    $function : This variable holds the name of a function that's associated with the search functionality.
    $placeholder : This variable contains the placeholder value for the search input field.
    $style : This variable contains a class name for the input field, providing it with a certain style.
    $attribute : This is an array of attribute values for the search input field. These could be any additional HTML attributes needed for the input field.
    $escape_key : This variable contains a function name that will be executed when the user presses the 'Escape' key.
--}}
@php
    empty($function) ? dd('Forget to pass function param in search input') : '';
@endphp

<input 
    id="search{{ $modelName }}"
    type="search" 
    wire:model.lazy="{{ $function }}" 
    placeholder="{{ empty($label) ? 'Search' : $label }}"
    class="form-control {{ empty($style) ? '' : $style }}"
    {{ empty($attribute) ? '' : implode(' ', $attribute)  }}
    wire:keydown.escape="escapeKeyDetect"
>
