@extends('panel::layouts.app')

@section('scripts')
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    <script>
        // console.log('Pusher script loaded');
        // Pusher.logToConsole = true;

        // Send from Pusher Debug Console for testing
        // const demoData = {
        //     "message":"Hello from Server"
        // };

        var pusherKey = '{{ env('PUSHER_APP_KEY') }}';
        var pusherChannel = '{{ env('PUSHER_APP_CHANNEL') }}';
        var pusherEvent = '{{ env('PUSHER_APP_EVENT') }}';
        
        var pusher = new Pusher(pusherKey, {
            cluster: 'ap2'
        });
        var channel = pusher.subscribe(pusherChannel);
        channel.bind(pusherEvent, function(data) {
            // alert(JSON.stringify(data));
            const messageContainer = document.getElementById('messageContainer');
            messageContainer.innerHTML += `<p>${data.message}</p>`;
        });
    </script>
@endsection

@section('main')
    <br>
    <h1>Pusher Test</h1>
    <div id="messageContainer"></div>
    <p>
        Try publishing an event to channel <code>Test-channel</code>
        with event name <code>Test-event</code>.
    </p>
@endsection
