{{--
    $label : The label used for the file input field.
    $model : The model object related to this input field.
    $attribute : An array of attribute values for the input field, such as 'multiple' or 'required'.
    $modelName : The name of the model, used for generating unique ids for the input fields.
    $name : The field name from the database table related to this input field.
    $type : The type attribute for the input field (default is 'file').
    $style : The CSS class name applied to style the input field.
    $message : The error message displayed when validation fails.
    $show : Boolean indicating whether to show or hide the input field (true shows the field).
    $options: Not used in this code snippet.
    $placeholder: Not used in this code snippet.
    $rows: Not used in this code snippet.
--}}
@php
    if(!empty($model)){
        if (($key = array_search('required', $attribute)) !== false) {
            unset($attribute[$key]);
        }
    }
@endphp 

<div class="mb-3">
    @if (!empty($label))  
        <label for="file{{ $modelName }}{{ $name }}" class="form-label">{{ $label }}</label>
    @endif

    @if (!$show)
        
        <input 
            id="file{{ $modelName }}{{ $name }}" 
            type="{{ empty($type) ? 'file' : $type }}" 
            class="form-control {{ empty($style) ? '' : $style }}" 
            name="{{ in_array('multiple',$attribute) ? $name.'[]' : $name }}" 
            {{ empty($attribute) ? '' : implode(' ', $attribute)  }}
            @if($show) disabled @endif
        >
        @error(in_array('multiple',$attribute) ? $name.'.*' : $name)
            <span class="input_val_error">{{ $message }}</span>
        @enderror

    @endif

</div>

{{-- Display Images --}}
@if(!empty($model))

    {{-- Secondary Images: based on attribute --}}
    @if(in_array('multiple',$attribute))

        @if ($model->getImages()->isNotEmpty() && !$show)
            <div class="mb-3">
                <label for="file{{ $modelName }}ImageDeleteLabel" class="form-label">{{ $messages['image_delete'] }}</label>
            </div>
        @endif

        <div class="mb-3 d-flex">
            @foreach ($model->getImages('s100', null, true) as $mediaItemId => $mediaItemUrl)
                <div class="form-check">
                    @if(!$show)
                        <input id="input{{ $modelName }}Image{{ $mediaItemId }}" type="checkbox" class="form-check-input"
                        name="delete_images[]" value="{{ $mediaItemId }}" />
                    @endif
                    <label class="form-check-label" for="input{{ $modelName }}Image{{ $mediaItemId }}">
                        <img src="{{ $mediaItemUrl }}" class="img-thumbnail" width="50" height="50" />
                    </label>
                </div>
            @endforeach
        </div>
        
    {{-- Primary Image, because its not secondary --}}
    @else

        <div class="mb-3">
            @if (!empty($model->getImage()))
                <img src="{{ $model->getImage() }}" class="img-thumbnail" width="50" height="50" />
            @endif
        </div>

    @endif

@endif