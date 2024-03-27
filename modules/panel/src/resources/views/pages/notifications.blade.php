@extends('panel::datatable')

@section('dashboard-content')

    @foreach ($notifications as $notification)
    
        <ul class="list-group flex-fill">
            {{-- @foreach ($notifications[$name]['unread'] as $notification) --}}
                @include('panel::includes.dashboard.notification-item-unread', [
                    'notification' => $notification->data
                ])
            {{-- @endforeach --}}
            {{-- @foreach ($notifications[$name]['read'] as $notification)
                @include('panel::includes.dashboard.notification-item-read')
            @endforeach --}}
        </ul>
    @endforeach

@endsection