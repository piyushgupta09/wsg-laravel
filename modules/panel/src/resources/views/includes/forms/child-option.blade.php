{{-- 
   $separator : A string used to visually separate a parent record from its child record(s) in the dropdown menu.
   $subrecords : Holds all child records associated with a parent record.
   $model : Represents the current model object that we're dealing with.
   hasChild() : A function that checks if a record has child records associated with it.
--}}
<div>
    @php $separator .= '-- '; @endphp
    @foreach ($subrecords as $subrecord)
        <option value="{{ $subrecord->id }}" @if (!empty($model) && $subrecord->id == $model->$name) selected @endif>{{ $separator }}{{ $subrecord->name }}</option>
        
        @if ($options['withRelation'] && method_exists($subrecord, $options['relation']) && $subrecord->{$options['relation']})
            @include('panel::includes.forms.child-option',[
                'subrecords' => $subrecord->{$options['relation']}, 
                'separator' => $separator, 
                'model' => empty($model) ? '' : $model,
            ])
        @endif
    @endforeach
</div>
