@if (isset($message))
    <div class="position-absolute top-0 bottom-0 start-0 end-0" 
        style="z-index: 5000; background-color: #00000050;">
        <div class="d-flex justify-content-center align-items-center min-vh-100">
            <div class="card shadow border-0">
                <div class="card-header py-1 d-flex justify-content-between text-bg-primary align-items-center">
                    <span class="modal-title font-title fw-bold flex-fill">Confirm Action</span>
                    <a type="button" class="btn" href="{{ $route }}">
                        <i class="bi bi-x-lg text-white"></i>
                    </a>
                </div>
                <div class="card-body">
                    {{ $message }}
                </div>
                <div class="card-footer d-flex justify-content-between">
                    <a type="button" class="btn flex-fill" href="{{ $route }}">Cancel</a>
                    <button type="button" class="btn text-bg-primary flex-fill" wire:click='confirmAction()'>Confirm</button>
                </div>
            </div>
        </div>
    </div>
@endif
