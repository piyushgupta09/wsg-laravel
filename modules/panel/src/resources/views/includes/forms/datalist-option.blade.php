<div class="form-floating mb-3 {{ empty($p_style) ? '' : $p_style }}">
    
    <input class="form-control {{ empty($style) ? '' : $style }}"
           list="datalist-{{ $modelName }}{{ $name }}" 
           id="floating{{ $modelName }}{{ $name }}" 
           placeholder="{{ $placeholder }}" 
           name="{{ $name }}"
           value="{{ !empty($model) ? $model->getTableData($name) : '' }}"
           {{ empty($attribute) ? '' : implode(' ', $attribute) }}
           @if($show) disabled @endif
    >
    
    <datalist id="datalist-{{ $modelName }}{{ $name }}">
        @foreach ($options['data'] as $record)
            <option>{{ $record['name'] }}</option>
        @endforeach
    </datalist>

    @if (!empty($note))  
        <small class="ps-2 font-quick">{{ $note }}</small>
    @endif

    <!-- Label for the datalist input field -->
    @if (!empty($label))
        <label for="floating{{ $modelName }}{{ $name }}" class="ps-4 font-quick">{{ $label }}</label>
    @endif
    
    <!-- If there is an error associated with this field, display the error message -->
    @error($name)
        <span class="input_val_error">{{ $message }}</span>
    @enderror
</div>
