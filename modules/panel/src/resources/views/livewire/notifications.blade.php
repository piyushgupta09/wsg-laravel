<div class="d-flex flex-column justify-content-center align-items-center">
    <button class="btn btn-outline-secondary border-0" 
    type="button" data-bs-toggle="offcanvas" data-bs-target="#toggleNotificationView"
        aria-controls="toggleNotificationView">

        <div class="d-flex justify-content-around align-items-center">
            <i class="bi bi-bell-fill lh-1"></i>
            @if ($hasNotification)
            <span class="ms-2 badge text-bg-danger badge-sm rounded-pill">
                {{ $unreadCount }}
                <span class="visually-hidden">unread messages</span>
            </span>
            @endif
        </div>

    </button>

    <div class="offcanvas offcanvas-end" tabindex="-1" id="toggleNotificationView"
        aria-labelledby="toggleNotificationViewLabel">
        <div class="offcanvas-header flex-column font-quick text-bg-dark pt-2 pb-0 px-2">
            <div class="d-flex justify-content-between p-2 align-items-center w-100">
                <p class="offcanvas-title ls-1 fw-bold" id="toggleNotificationViewLabel">
                    Notifications {{ $unreadCount ? '(' . $unreadCount . ')' : ''  }}</p>
                </p>
                <button type="button" class="btn" data-bs-dismiss="offcanvas" aria-label="Close">
                    <i class="bi bi-x-lg text-white ts-icon"></i>
                </button>
            </div>
        </div>
        <div class="offcanvas-body p-0 position-relative">
            <div class="d-flex flex-column h-100">

                <ul class="list-group flex-fill">

                    @if (array_key_exists('unread', $notifications) && !empty($notifications['unread']))
                    @foreach ($notifications['unread'] as $notification)
                    @include('panel::includes.dashboard.notification-item-unread')
                    @endforeach
                    @else
                    <div class="list-group-item rounded-0 text-center py-5">
                        <p class="mb-0">No Unread notifications !</p>
                    </div>
                    @endif

                    @if (array_key_exists('read', $notifications) && !empty($notifications['read']))
                    @foreach ($notifications['read'] as $notification)
                    @include('panel::includes.dashboard.notification-item-read')
                    @endforeach
                    @endif

                </ul>

                <div class="position-fixed bottom-0 end-0 m-4">
                    <a href="{{ route('notifications.index',[ 'type' =>  $type ]) }}" class="btn btn-dark opacity-50 rounded">
                        <div class="d-flex justify-content-center align-items-center h-100 fw-bold">
                            More <i class="ps-2 bi bi-chevron-right ts-icon"></i>
                        </div>
                    </a>
                </div>
                
            </div>
        </div>
    </div>

</div>