<div class="flex-fill w-100 h-100 overflow-hidden text-bg-light d-flex">
    @php
        $selectedPartyImage = $selectedParty->getMedia(Fpaipl\Brandy\Models\Party::MEDIA_COLLECTION_NAME)->isNotEmpty() ? $selectedParty->getFirstMediaUrl(Fpaipl\Brandy\Models\Party::MEDIA_COLLECTION_NAME, Fpaipl\Brandy\Models\Party::MEDIA_CONVERSION_THUMB) : config('panel.uia') . urlencode($selectedParty->business);
    @endphp
    <img src="{{ $selectedPartyImage }}" alt="{{ $selectedParty->business }}" class="wh-55" style="max-height: 55px">
    <div class="flex-fill d-flex flex-column justify-content-center px-2">
        <div class="d-flex">
            <span class="fw-bold">{{ $selectedParty->business }}</span>
            <span class="px-1">|</span>
            <span>{{ $selectedParty->user->name }}</span>
        </div>
        @if (get_class($selectedParty) == Fpaipl\Brandy\Models\Party::class)
            <p class="mb-0 small">{{ $selectedParty->address()?->print }}</p>
        @endif
    </div>
</div>
