{{-- 
    $action : Function name
    $bulkDisabled : Control enable and disable of button (this component access $bulkDisabled directly from livewire class in which it has been called upon)
    $style : Class name
    $title : It is a label
--}}


@if (!empty($confirm) && $confirm)

    @php
        if (!empty($queryName) && !empty($queryValue) && !empty($data)) {
            $route = route($action, [$data, $queryName => $queryValue]);
        } else {
            $route = route($action);
        }
    @endphp
    
    <button 
        type="button" 
        wire:click="setRoute('{{ $route }}')" 
        class="btn py-0 px-3 btn-outline-dark border-secondary {{ $style }}"
        title="{{ $title }}"
        data-bs-toggle="modal" data-bs-target="#confirmModal">
        @if (isset($icon))
            <i class="{{ $icon }}"></i>
        @else
            {{ $title }}
        @endif
    </button>    
@else
    <button 
        type="button" 
        title="{{ $title }}"
        wire:click="{{ $action }}" 
        class="btn px-3 btn-outline-dark {{ $style }} 
        @if (($action == 'deleteSelectedRecord' || $action == 'restoreSelectedRecords') && $bulkDisabled) disabled @endif"
    >
        @if (isset($icon))
            <i class="{{ $icon }}"></i>
        @else
            {{ $title }}
        @endif
    </button>
@endif


