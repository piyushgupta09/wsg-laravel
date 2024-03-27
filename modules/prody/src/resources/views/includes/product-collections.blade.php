<div class="ms-3 mt-3">
    <div class="d-flex flex-wrap">
        @if ($productCollections->isNotEmpty())
            @foreach ($productCollections as $productCollection)
            <div class="card me-3 mb-3">
                <div class="card-body p-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="card-title mb-0 text-capitalize me-3">{{ $productCollection->name }}</div>
                        <div class="card-title mb-0 fw-bold text-capitalize ms-3">{{ $this->getProductOption($productCollection->id)->name }}</div>
                        <div class="card-title mb-0 fw-bold text-capitalize ms-3">
                            <img src="{{ $this->getProductOption($productCollection->id)->getImage() }}" class="of-contain wh-80">
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        @else
            <div class="alert alert-warning">No product collection found for this product.</div>
        @endif
    </div>
</div>
