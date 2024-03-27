<div class="card border-0">
    <div class="card-header rounded-0 bg-dark py-2 d-flex justify-content-between align-items-center w-100">
        <div class="d-flex align-items-center flex-fill">
            <span class="font-quick text-white ls-1 fw-bold">Update Product Status</span>
        </div>
        <div class="btn-group">
            @foreach ($productStatus as $status)    
                <button 
                    class="btn border-0 text-white font-quick ls-1 fw-bold text-capitalize
                    {{ $product->status == $status ? 'btn-primary' : 'btn-secondary' }} }}" 
                    type="button" wire:click="updateStatus('{{ $status }}')">
                    {{ $status }}
                </button>
            @endforeach
        </div>
    </div>
</div>