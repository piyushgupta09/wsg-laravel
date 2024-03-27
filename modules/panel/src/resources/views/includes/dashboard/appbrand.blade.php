<nav class="navbar bg-light">
    <div class="mx-2 w-100">
        <a class="navbar-brand d-flex align-items-center py-0" href="{{ route('panel.welcome') }}">
            <img src="{{ asset('storage/assets/logo.png') }}" alt="Bootstrap" width="36" style="scale: 1.5">
            <span class="flex-fill d-flex flex-column ps-3 font-text lh-1">
                <div class="d-flex align-items-center">
                    <span class="fw-bold font-quick flex-fill">{{ config('brand.name') }}</span>
                    <span class="small text-muted">v1.0</span>
                </div>
                <span class="smaller text-muted pt-1">{{ config('brand.tagline') }}</span>
            </span>
      </a>
    </div>
</nav>