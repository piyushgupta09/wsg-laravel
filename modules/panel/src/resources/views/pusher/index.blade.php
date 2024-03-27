@extends('panel::layouts.app')

@section('main')
    <div class="fs-4 mb-2">Pusher Test</div>
    @if (Session::has('success'))
        <div class="fs-4 mb-2">Message has been sent successfully.</div>
        <a href="{{ route('pusher.show') }}">View message</a>
    @endif
    <div>
        <form method="post" action={{ route('pusher.push') }}>
            @csrf
            <input type="text" id="title" name="message" value="" required>
            <button type="submit">Save</button>
        </form>
    </div>
@endsection
