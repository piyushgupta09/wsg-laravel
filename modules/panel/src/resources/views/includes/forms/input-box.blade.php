{{-- 
   $label : The text to be displayed as the label of the input field.
   $modelName : The name of the Laravel model, used to generate a unique ID for the input field.
   $name : The attribute of the model that this input field corresponds to. Also serves as the 'name' attribute for the input field in HTML.
   $p_style : CSS class names to be added to the parent div of the input field.
   $type : The type of the input field (such as 'text', 'file', etc.).
   $style : CSS class names to be added to the input field.
   $attribute : An array of additional HTML attributes to apply to the input field.
   $placeholder : The placeholder text for the input field.
   $show : A boolean determining if the input field should be disabled or not.
   $model : The instance of the model. If provided, the model's corresponding value will be set as the input value. If not provided, Laravel's old() helper function is used to maintain the form state after validation errors.
   @error($name) : Laravel Blade directive. If there is a validation error for this input field, the error message is displayed.
--}}

@php
    if (!empty($model)) {
      if ($type == 'date') {
        $modelValue = \Carbon\Carbon::parse($model->$name)->format('Y-m-d');
      } else {
        $modelValue = $model->$name;
      }
    } else {
      $modelValue = isset($default) ? $default : old($name);
    }
@endphp

<div class="form-floating mb-3 {{ empty($p_style) ? '' : $p_style }}">

  <input 
    name="{{ $name }}" 
    @if($show) disabled @endif
    id="floating{{ $modelName }}{{ $name }}"
    type="{{ empty($type) ? 'text' : $type }}" 
    @if($type == 'number') step="any" @endif
    class="form-control {{ empty($style) ? '' : $style }}" 
    {{ empty($attribute) ? '' : implode(' ', $attribute)  }}
    value="{{ $modelValue }}" 
    placeholder="{{ empty($placeholder) ? '' : $placeholder }}" 
  >

  @if (!empty($note))  
    <small class="ps-2 font-quick">{{ $note }}</small>
  @endif

  @if (!empty($label))  
    <label for="floating{{ $modelName }}{{ $name }}" class="ps-4 font-quick">{{ $label }}</label>
  @endif

  <!-- Display validation error message if any -->
  @error($name)
      <span class="input_val_error">{{ $message }}</span>
  @enderror

</div>
