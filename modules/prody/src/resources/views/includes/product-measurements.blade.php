<div class="ms-3 mt-3">
    <div class="d-flex flex-wrap">
        @if ($productMeasurements->isNotEmpty())
            @foreach ($productMeasurements as $productMeasurement)
            <div class="card me-3 mb-3">
                <div class="card-body p-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="card-title mb-0 text-capitalize me-3">{{ $productMeasurement->name }}</div>
                        <div class="card-title mb-0 fw-bold text-capitalize ms-3">{{ $productMeasurement->value }}</div>
                    </div>
                </div>
            </div>
            @endforeach
        @else
            <div class="alert alert-warning">No measurements found for this product.</div>
        @endif
    </div>
</div>
