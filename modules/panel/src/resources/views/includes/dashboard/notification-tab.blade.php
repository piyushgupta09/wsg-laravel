<li class="nav-item" role="presentation">
    <button 
        id="{{ $name }}-tab" 
        type="button" role="tab" 
        class="nav-link fw-bold font-title {{ $first ? 'active' : '' }}" 
        data-bs-toggle="tab" 
        data-bs-target="#{{ $name }}-tab-pane"
        aria-controls="{{ $name }}-tab-pane" 
        aria-selected="{{ $first ? 'true' : 'false' }}"
    >
        <div class="d-flex align-items-center justify-content-around">
            <small class="text-capitalize">{{ $name }}</small>
            @if ($count)
                <span class="ms-1 badge rounded-pill text-bg-danger">
                    {{ $count }}
                </span>
            @endif
        </div>
    </button>
</li>
