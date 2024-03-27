<ul id="appMenuList" 
    class="nav nav-pills nav-sidebar flex-column" {{-- mb-3  --}}
    data-widget="treeview" role="menu" data-accordion="false">
    
    @foreach (config('panel.applinks') as $link)
        @include('panel::includes.dashboard.sidelink')
    @endforeach

</ul>

<ul id="userMenuList"
    class="nav nav-pills nav-sidebar flex-column" 
    data-widget="treeview" role="menu" data-accordion="false"
    style="margin-left: -0.5rem; /* compensating sidebar padding-start: 0.5rem */">

    <li class="d-flex align-items-center font-quick w-100 bg-brand">
        
        <button 
            type="button" class="nav-link text-start d-flex align-items-center ms-1 pe-0 flex-fill"
            id="dropdownUsermenu" data-bs-toggle="dropdown" aria-expanded="false"> 
            <div style="min-width: 50px;" class="text-center my-auto">
                <img src="{{ Auth::user()->getProfileImage() }}" alt="user profile image" 
                    width="32" height="32" class="nav-icon rounded-circle">
            </div>
            <div class="ps-2 pe-3 text-capitalize fw-bold text-white flex-fill align-items-center d-flex justify-content-between">
                {{ Auth::user()->name }}
                <i class="bi bi-chevron-up mt-1"></i>
            </div>
        </button>

        <ul id="userMenuListDropup" 
            class="dropdown-menu dropdown-menu-end dropdown-menu-lg-start shadow border-0 rounded-0" 
            aria-labelledby="dropdownUsermenu">

            @foreach (config('panel.userlinks') as $action)
                <li>
                    <a class="dropdown-item fw-500" href="{{ route($action['route']) }}">
                        {{ $action['name'] }}
                    </a>
                </li>
            @endforeach

            <li><hr class="dropdown-divider"></li>

            @foreach (config('panel.defaultinks') as $link)
                <li>
                    <a class="dropdown-item fw-500" href="{{ route($link['route']) }}">
                        {{ $link['name'] }}
                    </a>
                </li>
            @endforeach

            <li><hr class="dropdown-divider"></li>

            <li>
                <h5 class="dropdown-header text-capitalize text-start">
                    last Login: {{ Auth::user()->updated_at->format('h:m:s d M y') }}
                </h5>
            </li>

            <li>
                <form class="form-inline mb-0" action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button class="dropdown-item fw-500" type="submit">Logout</button>
                </form>
            </li>
        </ul>  
    </li>

</ul>

<style scoped>
    body.sidebar-mini.sidebar-collapse .nav-sidebar li .nav-link p {
        display: none;
    }
</style>