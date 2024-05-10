<div class="font-heading">
    <div class="row">
    
        <div class="col-md-6">
            <div class="card shadow-none overflow-hidden">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <p class="text-capitalize mb-0 fw-500">Delivery Type</p>
                        <p class="fw-bold text-capitalize mb-0">{{ $orderDelivery->type }}</p>
                    </div>
                </div>
                <div class="card-body p-0">
                    <table class="table table-sm table-borderless table-striped mb-0">
                        <tbody>
                            <tr>
                                <td class="ps-3 table-secondary">Name</td>
                                <th class="ps-3">{{ $orderDelivery->name }}</th>
                            </tr>
                            <tr>
                                <td class="ps-3 table-secondary">Contact</td>
                                <th class="ps-3">{{ $orderDelivery->contact }}</th>
                            </tr>
                            <tr>
                                <td class="ps-3 table-secondary">Scheduled Date/Time</td>
                                <th class="ps-3">{{ $orderDelivery->datetime }}</th>
                            </tr>
                            <tr>
                                <td class="ps-3 table-secondary">Note</td>
                                <th class="ps-3">{{ $orderDelivery->note }}</th>
                            </tr>
                            <tr>
                                <td class="ps-3 table-secondary">Secret</td>
                                <th class="ps-3">{{ $orderDelivery->secret }}</th>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow-none border">

                {{-- For Preview --}}
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        
                        <button class="btn btn-dark btn-sm" onclick="printContent('orderDeliveryAddress{{ $orderDelivery->id }}')">
                                <i class="bi bi-printer-fill pe-2"></i> Print
                            </button>

                        <p class="text-capitalize mb-0 fw-500 font-heading fw-bold">Delivery Info</p>

                        <div class="fw-bold font-title">
                            @if ($orderDelivery->status == 'pending')
                                <span class="font-normal">Status:</span> Pending
                            @endif
                            @if ($orderDelivery->status == 'delivered')
                                <span class="font-normal">Status:</span> Completed
                            @endif
                        </div>

                    </div>
                </div>
                <div class="card-body">

                     <div class="border-bottom pb-3">
                        <strong>{{ $orderDelivery->shipping_address }}</strong>
                    </div>

                    <div class="pt-3">

                        @if ($image && $orderDelivery->getFirstMediaUrl('delivery'))
                            <div class="d-flex justify-content-start align-items-end">
                                <img src="{{ $image }}" width="80" style="object-fit: cover" class="me-2 rounded">
                                <div class="d-flex flex-column">
                                    @if ($orderDelivery->delivered_at !== null)
                                        <p class="px-2 text-capitalize mb-0 fw-500 font-heading fw-bold">
                                            <span class="fw-normal">Delivered At: </span>
                                            {{ $orderDelivery->delivered_at }}
                                        </p>
                                    @endif
                                    <p class="mb-2 px-2 fw-bold font-title">Image of delivery challan for this order</p>
                                    <a class="btn btn-sm btn-dark" href="{{ $image }}" download>Download file</a>
                                </div>
                            </div>
                        @endif  

                        @if ($orderDelivery->status == 'pending')
                            <p class="mb-2 fw-bold font-title">Upload the delivery challan images in respect of this delivery only</p>

                            <div class="mb-3">
                                {{-- Preview --}}
                                <div class="div mb-2">
                                    @if ($image && $image->temporaryUrl())
                                        <img src="{{ $image->temporaryUrl() }}" width="200" style="object-fit: cover" class="me-2 rounded">
                                    @endif
                                </div>
                
                                {{-- Upload --}}
                                <input type="file" id="image" class="form-control" wire:model="image" required>
                            </div>
                            
                            @if ($error)
                                <span class="px-2 py-1 text-bg-danger error d-block mb-3" style="width: fit-content;">{{ $error }}</span>
                            @endif

                            <button class="btn text-bg-success w-100 fw-bold btn-sm" wire:click="deliveryCompleted({{ $orderDelivery->id }})">
                                <i class="bi bi-send pe-2"></i> Mark Delivered
                            </button>
                            
                        @endif
            
                    </div>
                </div>


                {{-- For Pintout --}}
                <div class="card-body d-none">
                    <div id="orderDeliveryAddress{{ $orderDelivery->id }}">
                        <div style="padding: 2rem;">
                            <span>Invoice (Order ID: {{ $orderDelivery->id }})</span>
                            <span>Invoice Amount: {{ $orderDelivery->id }}</span>
                            <hr>
                            <div class="small text-decoration-underline">Delivery Address</div>
                            <strong>{{ $orderDelivery->shipping_address }}</strong>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
        
    </div>
</div>

<script>
    function printContent(el) {
        var restorepage = document.body.innerHTML;
        var printcontent = document.getElementById(el).innerHTML;
        document.body.innerHTML = printcontent;
        window.print();
        document.body.innerHTML = restorepage;
    }
</script>