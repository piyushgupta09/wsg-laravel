{{-- 
    Radio Option Blade Component

    Description
    This Blade component renders a set of radio buttons for choosing the "active" status of a model. The component expects certain variables to be passed into it.

    Variables
    $model: The model object that contains the active field.
    $options['data']: An array with exactly two elements. The first element represents the label for the "Active" state, and the second element represents the label for the "Inactive" state.
    $options['default']: The default value to be checked if $model->active is not set.
    
    Usage
    Include this Blade component in your views and pass in the required variables:
    @include('path.to.radio-option', ['model' => $collection, 'options' => ['data' => ['Active', 'Inactive'], 'default' => 'Active']])

--}}
@php
    $isChecked = function($option, $index) use ($model, $options) {
        if (isset($model) && isset($model->active)) {
            return ($index === 0 && $model->active === 1) || ($index === 1 && $model->active === 0) ? 'checked' : '';
        }
        if (isset($options['default']) && $options['default'] === $option) {
            return 'checked';
        }
        return '';
    };
@endphp

<div class="d-flex flex-column mb-3 {{ $p_style }}">
    <div class="border rounded px-2 py-1">
        <div class="row">
            <div class="col-md-6">
                <div class="small text-muted">{{ $placeholder }}</div>
                <div class="d-flex justify-content-start px-2">
                    @foreach ($options['data'] as $index => $option)
                        <div class="form-check me-3 text-capitalize">
                            <input 
                                class="form-check-input" 
                                type="radio" 
                                name="{{ $name ?? 'active' }}" 
                                value="{{ $index === 0 ? 1 : 0 }}"
                                id="radioOption{{ $loop->index }}" 
                                @if($show) disabled @endif
                                {{ $isChecked($option, $index) }}
                            >
                            <label class="form-check-label" for="radioOption{{ $loop->index }}">{{ $option }}</label>
                        </div>
                    @endforeach
                </div>
            </div>
            @if ($note)
                <div class="col-md-6">
                    <div class="px-1 d-flex flex-column text-muted small">
                        {!! $note !!}
                    </div>
                </div>
            @endif
        </div>
        
    </div>
</div>

