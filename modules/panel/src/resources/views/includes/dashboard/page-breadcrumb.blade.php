@php
    $title = $modelName;
    switch ($title) {
        case 'sr': $title = 'Sale Return'; break;
        case 'pr': $title = 'Purchase Return'; break;
        case 'po': $title = 'Purchase Order'; break;
        case 'so': $title = 'Sale Order'; break;
        default: break;
    }
@endphp

@if ($formType == 'import')
    <div class="d-flex align-items-center">
        @push('page-breadcrumb')
            <li class="breadcrumb-item active" aria-current="page">
                <a 
                    href="{{ route(Str::plural($modelName) . '.index') }}" 
                    class="text-dark text-capitalize fw-500 td-none">
                    {{ Str::plural($title) }}
                </a>
            </li>
            <li class="breadcrumb-item">
                <div class="text-muted text-capitalize nowrap fw-500">{{ $formType }}</div>
            </li>
        @endpush
    </div>
@else
    <div class="d-flex align-items-center">
        @push('page-breadcrumb')
            @foreach (computeBreadcrumbs($modelName, $formType) as $link)
    
                @if ($loop->first)
                    <li class="breadcrumb-item active" aria-current="page">
                        <a href="{{ $link['url'] }}" class="text-dark fw-500 text-capitalize td-none">{{ $title }}</a>
                    </li>
                @endif

                @if (!$loop->first && !$loop->last)
                    <li class="breadcrumb-item active" aria-current="page">
                        <a href="{{ $link['url'] }}" class="text-dark fw-500 text-capitalize td-none">{{ $link['name'] }}</a>
                    </li>
                @endif

                @if ($loop->last)
                    <li class="breadcrumb-item me-2">
                        <div class="text-muted nowrap fw-500">{{ $link['name'] }}</div>
                    </li>
                @endif

            @endforeach
        @endpush
    </div>
@endif