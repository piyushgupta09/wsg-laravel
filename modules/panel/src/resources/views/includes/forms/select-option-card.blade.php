<div class="flex-fill w-100 h-100 overflow-hidden text-bg-ligth d-flex bg-md-start-{{ isset($selectedData['color']) ? $selectedData['color'] : '' }}">
    <div class="overflow-hidden" style="width: 60px; height: 60px;">
        <img src="{{ $selectedData['image'] }}" class="of-cover op-center" style="scale: 1.2;">
    </div>
    <div class="flex-fill d-flex flex-column justify-content-center px-2">
        <div class="d-flex">
            <span class="fw-bold">{{ $selectedData['title'] }}</span>
            <span class="px-1">|</span>
            <span>{{ $selectedData['detail'] }}</span>
        </div>
        <span>{{ $selectedData['subtext'] }}</span>
    </div>
    <input type="hidden" name="{{ $name }}" value="{{ $selectedData['id'] }}">
</div>
