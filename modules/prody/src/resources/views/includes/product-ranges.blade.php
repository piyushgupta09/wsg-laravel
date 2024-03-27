<div class="ms-3 mt-3">
    <div class="d-flex flex-wrap">
        @if ($productRanges->isNotEmpty())
            @foreach ($productRanges as $productRange)
            <div class="card me-3 mb-3">
                <div class="card-body p-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="card-title mb-0 fw-bold text-capitalize me-3">
                            <i class="bi bi-record-fill text-{{ $productRange->active ? 'success' : 'danger' }}"></i>
                            {{ $productRange->name }}
                        </div>
                        <div class="card-title mb-0 text-capitalize ms-3">
                            {{ $productRange->mrp }} | <strong>{{ $productRange->rate }}</strong>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        @else
            <div class="alert alert-warning">No range found for this product.</div>
        @endif
    </div>
</div>
