{{-- 
    $label : It ia used as label
    $model : It ia a model object
    Note:- Here we get a single image (primary image)
--}}
{{-- @if(!empty($label) && $model->getImage('s100') != '[]')
    {{ $label }} :
@endif --}}
@if ($model->getImage() != '[]')
    <div class="text-{{ $align }}">
        <div>
            <img src="{{ $model->getImage() }}" class="img-thumbnail" width="50" height="50" />
        </div>
    </div>
@endif
