@if (isset($message))
    <div class="position-fixed end-0 top-0 m-4">
        <div class="mt-5 p-3 rounded
        {{ $showError ? 'text-bg-danger' : ($showSuccess ? 'text-bg-success' : 'd-none') }}">
        <div class="d-flex justify-content-between align-items-center">
            <span class="pe-3">{{ $message }}</span>
            <button type="button" class="btn text-white border-0 btn-sm" wire:click="closeAlerts">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
        </div>
    </div>
@endif