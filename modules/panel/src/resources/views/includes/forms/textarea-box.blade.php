{{-- 
   $label : Label for the textarea input field.
   $modelName : Name of the model used to generate a unique ID for the textarea input field.
   $name : Name attribute of the textarea field, which corresponds to a column name in the database table.
   $style : CSS class names applied to the textarea field.
   $attribute : Array of additional HTML attributes to apply to the textarea field. 
   $placeholder : Placeholder text to be displayed in the textarea field when it's empty.
   $rows : Defines the number of visible text lines for the textarea field.
   $show : Determines if the textarea field should be disabled.
   $model : Model instance. If provided, the corresponding value from the model is displayed in the textarea field. If not, the old value is used (helpful in maintaining form state after validation errors).
   @error($name) : Laravel Blade directive. If there is a validation error associated with this field name, the error message will be displayed.
--}}
<div class="mb-3">
  @if (!empty($label))  
    <label class="ps-2 small fw-normal">{{ $label }}</label>
  @endif
  <textarea 
    class="form-control {{ empty($style) ? '' : $style }}"
    {{ empty($attribute) ? '' : implode(' ', $attribute)  }} 
    name="{{ $name }}" 
    placeholder="{{ empty($placeholder) ? '' : $placeholder }}" 
    id="input{{ $modelName }}{{ $name }}"
    rows="{{ $rows }}"
    @if($show) disabled @endif
   >{{ empty($model) ? ($default ? $default : old($name)) : $model->$name }}</textarea>

  <!-- Display validation error message if any -->
  @error($name)
      <span class="input_val_error">{{ $message }}</span>
  @enderror

</div>
