<div class="ms-3 mt-3">
    <div class="d-flex flex-wrap">
        @if ($productAttributes->isNotEmpty())
            @foreach ($productAttributes as $productAttribute)
            <div class="card me-3 mb-3">
                <div class="card-body p-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="card-title mb-0 text-capitalize me-3">{{ $productAttribute->name }}</div>
                        <div class="card-title mb-0 fw-bold text-capitalize ms-3">{{ $productAttribute->value }}</div>
                    </div>
                </div>
            </div>
            @endforeach
        @else
            <div class="alert alert-warning">No attributes found for this product.</div>
        @endif
    </div>
</div>
