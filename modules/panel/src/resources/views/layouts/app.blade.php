@extends('panel::layouts.master')

@section('top')

    <meta name="author" content="pg.softcode@gmail.com">
    <meta name="description" content="Its an internal managment system for Metro Fashion">
    <meta name="keywords" content="metro fashion, fpaipl, deshigirl, fashion passion">

    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

    <meta name="theme-color" content="#ea3941" />
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <link rel="apple-touch-icon" href="{{ asset('logo.png') }}" type="image/png">
    <link rel="apple-touch-startup-image" href="{{ asset('logo.png') }}">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-title" content="{{ config('app.name') }}">
    
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    @yield('styles')
    @livewireStyles()

    {{-- @auth
    	   <script src="{{ asset('js/enable-push.js') }}" defer></script>
	@endauth --}}

@endsection

@section('body')

    <div id="app">

        @yield('main')

        @if (request()->routeIs('panel.dashboard'))
            @include('panel::includes.pwa-install-alert')
            @vite('resources/js/pwa.js')
            @vite('resources/js/webpush.js')
        @endif

    </div>

@endsection

@section('bottom')

    @yield('scripts')
    @livewireScripts()


    {{-- <audio id="notification-bell">
        <source src="{{asset('storage/assets/notification.mp3')}}" type="audio/mpeg">
    </audio>    

    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    <script>
        var pusherKey = '{{ env('PUSHER_APP_KEY') }}';
        var pusherChannel = '{{ env('PUSHER_APP_CHANNEL') }}';
        var pusherEvent = '{{ env('PUSHER_APP_EVENT') }}';
        Pusher.logToConsole = true;
        var pusher = new Pusher(pusherKey, { cluster: 'ap2' });
        var channel = pusher.subscribe(pusherChannel);
        channel.bind(pusherEvent, function(data) {
            console.log(JSON.stringify(data));
            Livewire.dispatch('updateNotificationCount', data); 
            enableAutoplay();
        });

        var bell = document.getElementById("notification-bell");
        function enableAutoplay() {
            bell.autoplay = true;
            bell.load();
        }
    </script> --}}

@endsection
