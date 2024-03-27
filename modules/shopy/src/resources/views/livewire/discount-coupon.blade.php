<div class="">
    <div class="flex justify-between items-center">
        <h3 class="text-lg font-semibold">Discount Coupon Action</h3>

        <div class="card card-body">
            @if ($coupon->active)
                <div class="d-flex justify-content-between">
                    <button wire:click="shuffleCode" class="btn btn-primary fw-bold">Shuffle Code</button>
                    <button wire:click="deactivate" class="btn btn-warning fw-bold">De-Activate Coupon</button>
                    <button wire:click="delete" class="btn btn-danger fw-bold">Delete</button>
                </div>
            @else
                <button wire:click="activate" class="btn btn-success fw-bold">Activate Coupon</button>
            @endif
        </div>

        @if ($coupon->applicable == \Fpaipl\Shopy\Models\Coupon::APPLICABLE[1])
            <div class="row">
                <div class="col-md-6">
                    <div class="card card-body mt-3">
                        <div class="pb-2 font-title fw-500">
                            Search from the list of products to add them to this coupon elgiability.
                        </div>
                        @if ($allProducts->isNotEmpty())
                            <form method="post" wire:submit.prevent="addProduct" class="w-100 text-end">
                                @livewire('add-search-select', [
                                    'connection' => 'emit',
                                    'modelName' => 'product',
                                    'name' => 'product_id',
                                    'placeholder' => 'Select Product',
                                    'options' => [
                                        'model' => 'product',
                                        'route' => 'products.index',
                                        'data' => $allProducts,
                                    ],
                                    'label' => 'Select Product',
                                    'attribute' => ['required'],
                                    'note' => 'Select product to add to coupon',
                                    'style' => '', 'p_style' => '', 'show' => true
                                ], key($couponId))
                                <button type="submit" class="btn btn-primary px-4 fw-bold ms-auto">Add Product</button>
                            </form>
                        @else
                            <p>No products found</p>   
                        @endif
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card card-body mt-3">
                        @if ($couponProducts->isNotEmpty())
                            <div class="pb-2 font-title fw-500">
                                List of products added to this coupon.
                            </div>
                            <div class="d-flex flex-column">
                                @foreach ($couponProducts as $product)
                                    <div class="d-flex justify-content-between">
                                        <div class="flex-fill w-100 h-100 overflow-hidden text-bg-ligth d-flex">
                                            <div class="overflow-hidden" style="width: 60px; height: 60px;">
                                                <img src="{{ $product->getImage() }}" class="of-cover op-center" style="scale: 1.2;">
                                            </div>
                                            <div class="flex-fill d-flex flex-column justify-content-center px-2">
                                                <div class="d-flex">
                                                    <span class="fw-bold">{{ $product->name }}</span>
                                                    <span class="px-1">|</span>
                                                    <span>{{ $product->brand->name }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <button wire:click="removeProduct('{{ $product->sid }}')" class="btn">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        @else 
                            <p>No products added to this coupon</p>
                        @endif
                    </div>
                </div>
            </div>
        @endif

    </div>
</div>