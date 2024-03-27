<li class="nav-item ps-2 w-100 mb-2">

    <button 
        class="nav-link btn-toggle btn d-flex align-items-center text-white parent py-2 {{ isLinkActive($link['name']) }}" 
        data-bs-toggle="collapse" data-bs-target="{{ '#' . $link['id'] }}" aria-expanded="true">
        <i class="nav-icon pe-3 fs-5 {{ $link['icon'] }}"></i>
        <span class="ps-2 ls-1 mb-0 d-flex justify-content-between align-items-center flex-fill">
            <span>{{ $link['name'] }}</span>
            <i class="bi bi-chevron-right pt-1"></i>
        </span>
    </button>

    <div class="collapse" id="{{ $link['id'] }}">
        <ul class="btn-toggle-nav list-unstyled">
            @foreach ($link['child'] as $childlink)
                @if (auth()->user()->isSuperAdmin())
                    <li class="nav-item ms-3 mt-2">
                        <a href="{{ isset($childlink['route']) ? route($childlink['route']) : '' }}"
                            class="nav-link btn d-flex align-items-center text-white child py-2 {{ isChildLinkActive($childlink['route']) }}">
                            <i class="nav-icon pe-3 fs-5 bi bi-arrow-right-short"></i>
                            <span class="ps-2 ls-1 mb-0 d-flex justify-content-between align-items-center flex-fill">
                                <span>{{ $childlink['name'] }}</span>
                            </span>
                        </a>
                    </li>
                @else
                    @hasanyrole($childlink['access'])
                        <li class="nav-item ms-3 mt-2">
                            <a href="{{ isset($childlink['route']) ? route($childlink['route']) : '' }}"
                                class="nav-link btn d-flex align-items-center text-white child py-2 {{ isChildLinkActive($childlink['route']) }}">
                                <i class="nav-icon pe-3 fs-5 bi bi-arrow-right-short"></i>
                                <span class="ps-2 ls-1 mb-0 d-flex justify-content-between align-items-center flex-fill">
                                    <span>{{ $childlink['name'] }}</span>
                                </span>
                            </a>
                        </li>
                    @endhasanyrole
                @endif
            @endforeach
        </ul>
    </div>

</li>