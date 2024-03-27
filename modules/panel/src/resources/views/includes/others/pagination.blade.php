<div class="d-flex flex-column flex-md-row mt-3 mt-md-0 justify-content-between align-items-center">

    {{-- Per page count --}}
    <div class="mb-3 d-flex align-items-center border border-secondary rounded px-2">
        <div class="d-flex flex-column">
            <p class="mb-0 lh-1 font-quick small">Showing</p>
            <p class="mb-0 lh-1 font-quick small">Records</p>
        </div>
        <select id="select{{ $modelName }}RecordPerPage" class="form-select form-select-sm h-100 border-0 fs-5"
            wire:model="pageLength" style="width: fit-content">
            @foreach ($features['pagination']['attributes']['options'] as $option)
                <option value="{{ $option['value'] }}">{{ $option['name'] }}</option>
            @endforeach
        </select>
        <div class="d-flex flex-column justify-content-end">
            <p class="mb-0 lh-1 font-quick small">Total</p>
            <p class="mb-0 lh-1 font-quick small text-end">{{ $data->total() }}</p>
        </div>
    </div>

    {{-- links --}}
    @if ($pageLength <= $features['pagination']['attributes']['max'])
        {{ $data->links() }}
    @endif

</div>

<style>
    .pagination .page-item .page-link {
        color: var(--brand-system-text);
    }
    .pagination .page-item.active .page-link {
        color: var(--brand-primary-text);
        background-color: var(--brand-primary);
        border-color: var(--brand-primary);
    }
</style>