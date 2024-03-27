{{-- 
   $label : Label for the select input field.
   $modelName: Name of the model used to generate unique ID for the select input field.
   $name : Name attribute of the select field, which corresponds to a column name in the database table.
   $p_style : CSS class name applied to the parent <div>.
   $style : CSS class name applied to the select input field.
   $attribute : Array of additional HTML attributes to apply to the select input field.
   $placeholder : Placeholder value for the select input field.
   $options['data']: Array containing all the root level records to display as options in the select field.
   $separator : String used to visually distinguish parent options from child options.
   $model : Model instance. When provided, the corresponding value from the model will be selected in the select field.
   $options['withRelation']: Boolean to check whether the current record has a related child record or not.
   $options['relation']: String that indicates the name of the related child record.
   @error($name): Laravel Blade directive. If there is an error associated with this field name, the error message is displayed.
--}}
<div class="form-floating mb-3 {{ empty($p_style) ? '' : $p_style }}">

  <!-- Select Option will be pre selected in following 2 conditions: -->
  <!-- A. When a model is provided, and the corresponding field value matches the optionId. i.e. from database -->
  <!-- B. When a default is provided, and the corresponding field value matches the optionId. i.e. from datatable -->

  <select id="floating{{ $modelName }}{{ $name }}" 
    class="form-select {{ empty($style) ? '' : $style }}" 
    aria-label=".form-select example" 
    name="{{ $name }}"
    {{ empty($attribute) ? '' : implode(' ', $attribute)  }}
    @if($show) disabled @endif
  >
      <option selected value="">{{ $placeholder }}</option>
      @foreach ($options['data'] as $record)
          <?php $separator = ''; $option = (object) $record; ?>
          
          <option 
            value="{{ $option->id }}" 
            @if (!empty($model) && $option->id == $model->$name) selected @elseif (isset($options['default']) && $option->id == $options['default']) selected @endif
          >
            {{ $option->name }}
          </option>
          
          <!-- If the current option has a related child option, include the child options -->
          @if ($options['withRelation'] && method_exists($option, $options['relation']) && $option->{$options['relation']})
              @include('panel::includes.forms.child-option',[
                'subrecords' => $option->{$options['relation']}, 
                'separator' => $separator, 
                'model' =>empty($model) ? '' : $model,
              ])
          @endif

      @endforeach
  </select>

  @if (!empty($note))  
    <small class="ps-2 font-quick">{{ $note }}</small>
  @endif

  <!-- Label for the select input field -->
  @if (!empty($label))  
    <label for="floating{{ $modelName }}{{ $name }}" class="ps-4 font-quick">{{ $label }}</label>
  @endif

  <!-- If there is an error associated with this field, display the error message -->
  @error($name)
      <span class="input_val_error">{{ $message }}</span>
  @enderror

</div>
