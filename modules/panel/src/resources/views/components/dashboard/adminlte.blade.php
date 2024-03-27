<div class="d-flex w-100">

    <!-- Sidebar Desktop -->
    <div class="d-none d-md-block">
        <div class="d-flex flex-column bg-darker overflow-y-auto" style="width: 280px; height: 100vh;">
            @include('panel::includes.dashboard.appbrand')
            @include('panel::includes.dashboard.sidemenu')
            @include('panel::includes.dashboard.usermenu')
        </div>
    </div>

    <!-- Sidebar Mobile -->
    <div class="d-block d-md-none">
        <div class="offcanvas offcanvas-start w-100" tabindex="-1" id="sideMenuCanvas">
            <div class="offcanvas-body bg-darker p-0">
                <div class="d-flex flex-column justify-content-between h-100">
                    @include('panel::includes.dashboard.appbrand')
                    @include('panel::includes.dashboard.sidemenu')
                    @include('panel::includes.dashboard.usermenu')
                </div>
            </div>
        </div>
    </div>

    <!-- Page Content -->
    <div class="flex-fill d-flex flex-column">
        @include('panel::includes.dashboard.navbar')
        <div class="flex-fill p-3">
            {{ $slot }}
        </div>
    </div>

</div>
