<nav class="navbar bg-body-tertiary border border-start-0">
    <div class="container-fluid">
        <div class="d-flex justify-content-between w-100">

            <div class="d-flex align-items-center me-auto">

                <button 
                    type="button" 
                    class="d-inline-block d-md-none btn border-0 py-0" 
                    data-bs-toggle="offcanvas" 
                    data-bs-target="#sideMenuCanvas" 
                    aria-controls="sideMenuCanvas">
                    <i class="bi bi-list fs-4"></i>
                </button>

                @stack('model-sid')

                <a class="btn border-0 text-capitalize font-quick" href="{{ url()->current() }}">
                    @stack('page-title')
                </a>

                <nav class="btn text-start px-0 border-0 font-quick" aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        @stack('page-breadcrumb')
                    </ol>
                </nav>

            </div>

            <div class="d-flex align-items-center ms-auto">

                <div class="btn-group dropstart">
                    <button type="button" class="btn btn-outline-secondary border-0 rounded dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="d-flex align-item-center fs-6 lh-1">
                            <span class="visually-hidden">Create New Actions</span>
                            <i class="bi bi-plus-lg ts-icon"></i>
                            <span class="ms-1 fw-bold">Quick</span>
                        </div>
                    </button>
                    <ul class="dropdown-menu">
                        @foreach (config('panel.create-actions') as $action)
                            @if (auth()->user()->isSuperAdmin())
                                <li><a class="dropdown-item" href="{{ route($action['route']) }}">{{ $action['name'] }}</a></li>
                            @endif
                            @hasanyrole($action['access'])
                                <li><a class="dropdown-item" href="{{ route($action['route']) }}">{{ $action['name'] }}</a></li>
                            @endhasanyrole
                        @endforeach
                    </ul>
                </div>

                @include('panel::includes.dashboard.fullscreen')

                @php
                    $userRoles = Auth::user()->getRoleNames()->pluck('name')->toArray();
                @endphp

                @if (in_array('user', $userRoles))
                    @livewire('notifications')
                @endif

            </div>

        </div>
    </div>
</nav>
