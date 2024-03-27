<div class="tab-pane h-100 fade {{ $first ? 'show active' : '' }}" id="{{ $name }}-tab-pane"
    role="tabpanel" aria-labelledby="{{ $name }}-tab" tabindex="0">
    <div class="d-flex flex-column h-100">
        <ul class="list-group flex-fill">

            @foreach ($notifications['unread'] as $notification)
                <div class="list-group-item rounded-0 px-3 py-2 border-bottom btn" 
                    style="background-color: rgb(162, 193, 246)"
                    wire:click="markAsRead('{{ $notification['id'] }}')"
                >
                    <p class="mb-0 fw-bold font-quick text-uppercase ls-1">{{ $notification['title'] }}</p>
                    <p class="mb-0 font-quick">{{ $notification['message'] }}</p>
                    <p class="mb-0 smaller font-quick text-end">{{ $notification['time'] }}</p>
                </div>
            @endforeach

            @foreach ($notifications['read'] as $notification)
                <div class="list-group-item rounded-0 px-3 py-2 border-bottom btn" 
                    style="background-color: rgb(162, 193, 246)"
                    wire:click="markAsRead('{{ $notification['id'] }}')"
                >
                    <p class="mb-0 fw-bold font-quick text-uppercase ls-1">{{ $notification['title'] }}</p>
                    <p class="mb-0 font-quick">{{ $notification['message'] }}</p>
                    <p class="mb-0 smaller font-quick text-end">{{ $notification['time'] }}</p>
                </div>
            @endforeach

        </ul>
        <a href="{{ route('notifications',[ 'type' =>  $name ]) }}" 
            class="btn btn-outline-dark rounded-0">
            Show More
        </a>
    </div>
</div>
