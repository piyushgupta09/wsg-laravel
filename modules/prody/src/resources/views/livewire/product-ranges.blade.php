<div class="card border-0 mb-3">

    <div class="card-header rounded-0 bg-dark py-2 d-flex justify-content-between align-items-center w-100">
        <div class="d-flex align-items-center flex-fill">
            <button class="btn border-0 ps-0 text-white" type="button" data-bs-toggle="collapse"
                data-bs-target="#productRangeList" aria-expanded="true" aria-controls="productRangeList">
                <i class="bi bi-chevron-down me-2"></i>
                <span class="font-quick ls-1 fw-bold">Product Ranges (Sizes)</span>
            </button>
        </div>
        <div class="">
            <button class="btn border-0 text-white font-quick ls-1 fw-bold" type="button" wire:click='reloadData'>
                <i class="bi bi-arrow-clockwise"></i>
            </button>
            <button class="btn border-0 text-white font-quick ls-1 fw-bold" type="button" wire:click="toggleForm">
                <span x-data="{ show: @entangle('showForm') }">
                    <i x-show="show" class="bi bi-x-lg"></i>
                </span>
                <span x-data="{ show: @entangle('showForm').defer }">
                    <i x-show="!show" class="bi bi-plus-lg"></i>
                </span>
            </button>      
        </div>
    </div>

    <div x-data="{ show: @entangle('showForm') }" x-show="show" x-transition.delay.100ms
        class="card-body px-2 text-bg-secondary">

        <form wire:submit.prevent="{{ $formType === 'create' ? 'store' : 'update' }}">

            <div class="row g-2 m-0">

                <p class="font-robot ls-1">
                    @if ($formType == 'create')
                    Create New Range
                    @else
                    Edit Range
                    @endif
                </p>

                {{-- Select Range Type --}}
                <div class="col-md-4 mb-3">
                    <div class="form-floating">
                        <select class="form-select" id="productRangeType" wire:model="rangeType" required
                            aria-label="Choose range type">
                            <option selected>Select Type</option>
                            @foreach ($ranges as $key => $type)
                            <option value="{{ $key }}">{{ $type }}</option>
                            @endforeach
                        </select>
                        <label for="productRangeType" class="font-quick text-dark font-normal">Product Range</label>
                        @error('rangeType')
                        <span class="text-bg-danger error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                {{-- Product Range Mrp --}}
                <div class="col-md-4 mb-3">
                    <div class="form-floating">
                        <input type="text" id="productRangeMrp" class="form-control" wire:model="rangeMrp" required>
                        <label for="productRangeMrp" class="font-quick text-dark font-normal">
                            Market Rate (mrp)
                        </label>
                        @error('rangeMrp')
                        <span style="font-size: 0.75rem" class="text-bg-danger error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                {{-- Product Range Rate --}}
                <div class="col-md-4 mb-3">
                    <div class="form-floating">
                        <input type="text" id="productRangeRate" class="form-control" wire:model="rangeRate" required>
                        <label for="productRangeRate" class="font-quick text-dark font-normal">
                            Selling Rate (rate)
                        </label>
                        @error('rangeRate')
                        <span style="font-size: 0.75rem" class="text-bg-danger error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                {{-- Attach material option color to material as per product option --}}
                @if ($rangeType)

                    @foreach ($productMaterials as $productMaterial)

                        {{-- Product Material --}}
                        <div class="col-12">
                            <p class="font-quick text-white fw-bold mb-2 ps-1 ls-1 text-capitalize">
                                {{ $loop->iteration }}. Material Code: {{ $productMaterial->material->sid }} | {{
                                $productMaterial->material->name }} {{ $productMaterial->grade ? ' | ' . $productMaterial->grade :
                                '' }}
                            </p>
                        </div>

                        <div class="col-12">
                            <div class="card mb-2">
                                <div class="card-body p-2">
                                    <div class="row g-3 m-0 pb-2">
        
                                        {{-- Material Consumption Name --}}
                                        <div class="col-lg-4">
                                            <div class="form-floating">
                                                <input
                                                    id="productMaterialConsumptionName{{ $productMaterial->id }}_0"
                                                    type="text" class="form-control" required
                                                    wire:model="consumption.{{ $productMaterial->id }}_0.name">
                                                <label
                                                    for="productMaterialConsumptionName{{ $productMaterial->id }}_0"
                                                    class="font-quick text-dark font-normal">
                                                    {{ $loop->iteration }}. Product Material Name</span>
                                                </label>
                                                @error('consumption.' . $productMaterial->id . '_0.name')
                                                    <span style="font-size: 0.75rem" class="text-bg-danger error">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
        
                                        {{-- Material Consumption Unit --}}
                                        <div class="col-lg-4">
                                            <div class="form-floating">
                                                <select class="form-select" required
                                                    id="productMaterialConsumptionUnit{{ $productMaterial->id }}_0"
                                                    wire:model="consumption.{{ $productMaterial->id }}_0.unit">
                                                    <option value="">Select Unit</option>
                                                    @foreach (config('panel.units.fcpu') as $unit)
                                                        <option value="{{ $unit }}">{{ $unit }}</option>
                                                    @endforeach
                                                </select>
                                                <label
                                                    for="productMaterialConsumptionUnit{{ $productMaterial->id }}_0"
                                                    class="font-quick text-dark font-normal">
                                                    {{ $loop->iteration }}. Consumption Unit
                                                </label>
                                                @error('consumption.' . $productMaterial->id . '_0.unit')
                                                <span style="font-size: 0.75rem" class="text-bg-danger error">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
        
                                        {{-- Material Cost --}}
                                        <div class="col-lg-4">
                                            <div class="form-floating">
                                                <input
                                                    id="productMaterialCost{{ $productMaterial->id }}_0"
                                                    type="numeric" class="form-control" required
                                                    wire:model="consumption.{{ $productMaterial->id }}_0.cost">
                                                <label for="productMaterialCost{{ $productMaterial->id }}_0" class="font-quick text-dark font-normal">
                                                    {{ $loop->iteration }}. Material Cost Price
                                                </label>
                                                @error('consumption.' . $productMaterial->id . '_0.cost')
                                                    <span style="font-size: 0.75rem" class="text-bg-danger error">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
        
                                        @foreach ($productMaterial->material->materialRanges as $productMaterialRange)
                                            <div class="col-12">
                                                <div class="input-group">
                                                    <span class="input-group-text" id="productMaterialConsumptionQty{{ $productMaterial->id }}_{{ $productMaterialRange->id }}">
                                                        <div class="form-check">
                                                            <input 
                                                                class="form-check-input" type="checkbox"
                                                                wire:model='consumption.{{ $productMaterial->id }}_{{ $productMaterialRange->id }}.active'
                                                                id="productMaterialRangeActive{{ $productMaterial->id }}_{{ $productMaterialRange->id }}"
                                                            >
                                                            <label class="form-check-label font-quick text-dark font-normal" for="productMaterialRangeActive{{ $productMaterial->id }}_{{ $productMaterialRange->id }}">
                                                                <span class="text-capitalize">{{ getChar($loop->iteration) }}. </span>
                                                                <span>
                                                                    Width: {{ $productMaterialRange->width }} | Length: {{ $productMaterialRange->length }} | Rate: {{ $productMaterialRange->rate }}
                                                                </span>
                                                            </label>
                                                        </div>
                                                    </span>
                                                    <input 
                                                        type="text" class="form-control" placeholder="Enter FCPU here"
                                                        {{ $consumption[$productMaterial->id . '_' . $productMaterialRange->id]['active'] ? 'required' : 'disabled'  }}
                                                        id="productMaterialConsumptionQty{{ $productMaterial->id }}_{{ $productMaterialRange->id }}"
                                                        wire:model="consumption.{{ $productMaterial->id }}_{{ $productMaterialRange->id }}.qty"
                                                    >
                                                </div>
                                            </div>
                                        @endforeach
        
                                    </div>
                                </div>
                            </div>
                        </div>

                    @endforeach

                @endif

                {{-- Action Buttons --}}
                <div class="btn-group w-50 ms-auto">
                    <button type="button" wire:click.prevent="resetForm()" class="btn btn-outline-light">Reset</button>
                    <button type="submit" class="btn btn-info">{{ $formType === 'create' ? 'Save' : 'Update' }}</button>
                </div>

            </div>

        </form>
    </div>

    <div class="collapse show" id="productRangeList">
        <ul class="list-group list-group-flushed">
            @foreach($productRanges as $key => $productRange)
            <li class="list-group-item rounded-0">

                <div class="d-flex">

                    <div class="flex-fill d-flex align-items-center font-quick">
                        <span style="width: 20px">{{ $loop->iteration }}</span>
                        <p class="mb-0 text-capitalize fw-bold">
                            {{ $productRange->name }} | Rs. {{ $productRange->rate }}</p>
                    </div>

                    <div class="btn-group">
                        <button 
                            class="btn border-dark py-1 px-3
                            {{ $productRange->active ? 'btn-success' : 'btn-danger' }}" 
                            type="button"
                            wire:click.prevent="stockout({{ $productRange->id }})">
                            @if ($productRange->active)
                                <i class="bi bi-cart-check"></i>
                            @else
                                <i class="bi bi-cart-x"></i>
                            @endif
                        </button>
                        <button class="btn btn-outline-dark border-secondary py-1 px-3" type="button"
                            data-bs-toggle="collapse" data-bs-target="#collapseRangeComsumption{{ $key }}"
                            aria-expanded="false" aria-controls="collapseRangeComsumption{{ $key }}">
                            <i class="bi bi-eye"></i>
                        </button>
                        <button 
                                class="btn btn-outline-dark border-secondary py-1 px-3" type="button"
                                wire:click.prevent="clone({{ $productRange->id }})">
                                <i class="bi bi-copy"></i>
                            </button>
                        <button class="btn btn-outline-dark border-secondary py-1 px-3" type="button"
                            wire:click.prevent="edit({{$productRange->id}})">
                            <i class="bi bi-pencil-square"></i>
                        </button>
                        <button class="btn btn-outline-dark border-secondary py-1 px-3" type="button"
                            wire:click.prevent="delete({{$productRange->id}})">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>

                </div>

                <div class="collapse" id="collapseRangeComsumption{{ $key }}">
                    <div class="row g-2 m-0">
                        @foreach ($productRange->pomrs as $pomr)

                        {{-- Product Material --}}
                        <div class="col-6 mb-3">
                            <div class="form-floating">
                                <input type="text" class="form-control" disabled
                                    value="{{ $pomr->id }}">
                                    {{-- value="{{ $pomr->materialRange->material->name }} | {{ $pomr->materialOption->name }}"> --}}
                                <label class="font-quick text-dark font-normal">Material Consumed</label>
                            </div>
                        </div>

                        {{-- Material Consumption Rate --}}
                        <div class="col-6 mb-3">
                            <div class="form-floating">
                                <input type="text" class="form-control" disabled
                                    value="{{ $pomr->quantity }} {{ $pomr->unit }}">
                                <label class="font-quick text-dark font-normal">Material Consumption Rate</label>
                            </div>
                        </div>

                        @endforeach
                    </div>
                </div>

            </li>
            @endforeach
        </ul>
    </div>

</div>