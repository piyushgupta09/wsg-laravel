@if (isset($notification['action']))    
    <a class="list-group-item rounded-0 px-3 py-2 border-bottom btn text-start" href="{{ $notification['action'] }}">
        <div class="d-flex justify-content-between align-items-start">
            <p class="mb-0 fw-bold small font-quick text-capitalize">{{ $notification['title'] }}</p>
            <p class="mb-0 smaller font-quick text-end">{{ \Carbon\Carbon::parse($notification['time'])->diffForHumans() }}</p>
        </div>
        <p class="mb-0 font-title">{{ $notification['message'] }}</p>
    </a>
@endif