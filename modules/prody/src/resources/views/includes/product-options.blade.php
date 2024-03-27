<div class="ms-3 mt-3">
    <div class="d-flex flex-wrap">
        @if ($productOptions->isNotEmpty())
            @foreach ($productOptions as $productOption)
            <div class="card me-3 mb-3 position-relative" style="width: 150px">
                @if ($productOption->hasImage('s400'))
                    <img src="{{ $productOption->getImage('s400') }}" class="card-img-top" >
                @endif
                <div class="card-body p-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="wh-20 rounded-circle" style="background-color: {{ $productOption->code }}"></div>
                        <div class="card-title mb-0 fw-bold text-capitalize">{{ $productOption->name }}</div>
                    </div>
                </div>
                <div class="position-absolute tpo-0 end-0 mt-2 fw-bold font-title ps-2 pe-1 smallest br-start-pill text-bg-{{ $productOption->active ? 'success' : 'danger' }}">Live</div>
            </div>
            @endforeach
        @else
            <div class="alert alert-warning">No options found for this product.</div>
        @endif
    </div>
</div>
