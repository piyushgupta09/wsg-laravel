<div class="d-flex rounded w-100 h-100 align-items-start mb-3" style="position: relative; z-index: 10;">

    <button type="button" class="btn btn-light rounded-0 rounded-start border py-3">
        <i class="bi bi-search px-2"></i>
    </button>

    <div class="flex-fill h-100" style="height: 59px !important; position: relative;">
        @if ($selectedData)
            <div class="d-flex justify-content-between align-items-center border-top border-bottom overflow-hidden"
                style="height: 59px !important;">
                @include('panel::includes.forms.select-option-card', [
                    'selectedData' => $selectedData,
                ])
                <button class="btn btn-light h-100 border-0 rounded-0" wire:click.prevent="removeData">
                    <i class="bi bi-x-lg text-danger px-2"></i>
                </button>
            </div>
        @else
            <input 
                id="dataSearchInput" 
                type="search" 
                wire:model="search"
                class="form-control border-0 border-top border-bottom rounded-0 text-bg-light"
                style="width: 100%; height: 100%;"
                placeholder="{{ $placeholder }}"
                {{ implode(' ', $attribute)}}
                autocomplete="off"
            > 
            @if ($this->filteredData->isNotEmpty())
                <ul class="list-group rounded-0 shadow">
                    @foreach ($this->filteredData as $data)
                        <li class="list-group-item list-group-flush px-2" wire:click="selectData({{ $data['id'] }})">
                            @include('panel::includes.forms.select-option-card', [
                                'selectedData' => $data,
                            ])
                        </li>
                    @endforeach
                </ul>
            @else
                @if (strlen($search) > 2)
                    <div class="p-2 fw-bold text-bg-danger">No results found</div>
                @endif
            @endif
        @endif
    </div>

    @if (isset($modelCreateRoute) && Route::has($modelCreateRoute))
        @if (strpos($modelCreateRoute, 'create') !== false)
            <a class="btn btn-light rounded-0 rounded-end border py-3" href="{{ route($modelCreateRoute) }}">
                <i class="bi bi-plus-lg px-2"></i>
            </a>
        @else
            @if ($this->filteredData->isNotEmpty())
                <button class="btn btn-light rounded-0 rounded-end border py-3" 
                    wire:click="closeAllData">
                    <i class="bi bi-chevron-up px-2"></i>
                </button>
            @else
                <button class="btn btn-light rounded-0 rounded-end border py-3" 
                    wire:click="showAllData">
                    <i class="bi bi-chevron-down px-2"></i>
                </button>
            @endif
        @endif
    @endif

</div>



