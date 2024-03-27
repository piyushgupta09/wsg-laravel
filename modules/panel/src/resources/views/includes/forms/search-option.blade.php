<div class="">
    
    @php
        $isEditPage = Str::contains(request()->path(), 'edit');
    @endphp

    @if (isset($model))        
        @if ($isEditPage)
            @livewire(
                'add-search-select',
                [
                    'selectedData' => $model->getTableData($name . '_select'),
                    'modelName' => $modelName,
                    'name' => $name,
                    'placeholder' => $placeholder,
                    'options' => $options,
                    'attribute' => $attribute,
                    'note' => $note,
                    'label' => $label,
                    'style' => $style,
                    'p_style' => $p_style,
                    'show' => $show,
                ],
                key($user->id)
            )
        @else
            <div class="mb-3 border rounded overflow-hidden">
                @include('panel::includes.forms.select-option-card', [
                    'selectedData' => $model->getTableData($name . '_select'),
                ])
            </div>
        @endif
    @else
        @livewire(
            'add-search-select',
            [
                'modelName' => $modelName,
                'name' => $name,
                'placeholder' => $placeholder,
                'options' => $options,
                'attribute' => $attribute,
                'note' => $note,
                'label' => $label,
                'style' => $style,
                'p_style' => $p_style,
                'show' => $show,
            ],
            key($user->id)
        )
    @endif
</div>
