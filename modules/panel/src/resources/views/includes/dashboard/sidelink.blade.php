<li class="nav-item ps-2 w-100 mb-2">
    <a 
        href="{{ isset($link['route']) ? route($link['route']) : '' }}"
        class="btn nav-link d-flex align-items-center text-white parent {{ isLinkActive($link['name']) }}">
        <i class="nav-icon pe-3 fs-5 {{ $link['icon'] }}"></i>
        <p class="ps-2 ls-1 mb-0">{{ $link['name'] }}</p>
    </a>
</li>
